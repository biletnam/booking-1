<?php

class Database
{
    private $db; // an open connection to the database
    
    function __construct()
    {
        try
        {
            $this->db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                                 charset=UTF8', MYSQL_USER, MYSQL_PASS);
        }
        catch (Exception $e)
        {
            printf("<div id=\"warning\">%s</div>", $e->getMessage());
        }
    }

    /**
     * Remove a reservation in the database.
     * @param the application context (reservation + db)
     * @param the reservation ID
     * @return none
     */
    function delete(&$ctx, $id)
    {
        $request = "DELETE FROM reservation WHERE id=".$id.";";

        if ($this->db->exec($request) == 0)
            $ctx['warning'] .= "L'identifiant de réservation n'existe pas\n";
        else
            $ctx['warning'] .= "La réservation ".$id." a été ".
                               "supprimée avec succès.\n";
    }

    /**
     * Retrieve one Reservation from the database.
     * @param the application context (reservation + db)
     * @param the reservation ID
     * @return the retrieved Reservation
     */
    function selectOne(&$ctx, $id)
    {  
        $x = new Reservation();

        $request = "SELECT * FROM reservation WHERE id=".$id.";";

        foreach ($this->db->query($request) as $data)
        { 
            // this should not be looped more than once but the structure
            // of pdostatement is kind of messy so I'm using a foreach.

            if ($data == false)
                $ctx['warning'] .= "L'identifiant de réservation n'existe pas\n";
            else
            {
                $x->id             = $data['id'];
                $x->destination    = $data['destination'];
                $x->insurance      = intval($data['insurance']) ? 'True':'False';
                $x->personsCounter = intval($data['nbr_persons']);
                $x->persons        = unserialize(base64_decode($data['persons']));
                $x->save();
            }
        }

        return $x;
    }

    /**
     * Retrieve all the Reservation from the database.
     * @param none
     * @return an array with all the reservation from the database
     */
    function selectAll()
    {  
        $reservations = array();

        $request = "SELECT * FROM reservation;";

        foreach ($this->db->query($request) as $row)
        {
            $x = new Reservation();

            $x->id             = $row['id'];
            $x->destination    = $row['destination'];
            $x->insurance      = intval($row['insurance']) ? 'Oui':'Non';
            $x->personsCounter = $row['nbr_persons'];
            $x->price          = $row['price'];
            $x->persons        = unserialize(base64_decode($row['persons']));

            array_push($reservations, $x);
        }

        return $reservations;
    }

    /**
     * Save the reservation in the database.
     * @param the application context (reservation + db)
     * @return none
     */
    function insert(&$ctx)
    {
        $reservation = $ctx['reservation'];
        $reservation->calculateAmount();

        $encodedPersons = base64_encode(serialize($reservation->persons));

        $request = "INSERT INTO reservation
                    SET price=$reservation->price,
                        insurance=$reservation->insurance,
                        destination='$reservation->destination',
                        nbr_persons=$reservation->personsCounter,
                        persons='".$encodedPersons."';";

        if ($this->db->exec($request) == 0)
            $ctx['warning'] .= "Rien n'a été enregistré.\n";
    }

    /**
     * Update the reservation in the database.
     * @param the application context (reservation + db)
     * @return none
     */
    function update(&$ctx)
    {
        $reservation = $ctx['reservation'];
        $reservation->calculateAmount();

        $encodedPersons = base64_encode(serialize($reservation->persons));

        $request = "UPDATE reservation
                    SET price=$reservation->price,
                        insurance=$reservation->insurance,
                        destination='$reservation->destination',
                        nbr_persons=$reservation->personsCounter,
                        persons='".$encodedPersons."'
                    WHERE id=$reservation->id;";

        if ($this->db->exec($request) == 0)
            $ctx['warning'] .= "Aucune données mise à jour.\n";
    }

    /**
     * Generic getter
     * @param the name of the property to return
     * @return the value of the property
     */
    public function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }

    /**
     * Generic setter
     * @param the name of the property to set
     * @param the new value to set
     * @return none
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
        {
            $this->$property = $value;
        }
    }
}

?>
