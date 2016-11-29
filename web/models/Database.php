<?php

class Database
{
    private $db; // open connection to the database
    
    function __construct()
    {
        $this->db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                             charset=UTF8', MYSQL_USER, MYSQL_PASS);
    }

    /**
     * Remove a reservation in the database.
     * @param the reservation context
     * @return none
     */
    function delete($reservation)
    {
        $id = intval($_GET['id']);

        $request = "DELETE FROM reservation WHERE id=".$id.";";

        if ($this->db->exec($request) == 0)
            $reservation->append_warning("L'identifiant de réservation n'existe pas\n");
        else
            $reservation->append_warning("La réservation ".$id." a été ".
                                         "supprimée avec succès.\n");
    }

    /**
     * Retrieve one Reservation from the database.
     * @param the reservation context to fill
     * @param the id of the reservation
     * @return none
     */
    function select_one($reservation)
    {  
        $id = intval($_GET['id']);

        $request = "SELECT * FROM reservation WHERE id=".$id.";";

        foreach ($this->db->query($request) as $data)
        { 
            // this should not be looped more than once but the structure
            // of pdostatement is kind of messy so I'm using a foreach.

            if ($data == false)
                $reservation->append_warning("L'identifiant de réservation n'existe pas\n");
            else
            {
                $reservation->reset();
                $reservation->id             = intval($data['id']);
                $reservation->destination    = $data['destination'];
                $reservation->insurance      = intval($data['insurance']) ? 'True':'False';
                $reservation->personsCounter = intval($data['nbr_persons']);
                $reservation->persons        = unserialize(base64_decode($data['persons']));
                $reservation->editionMode    = true;
                $reservation->save();
            }
        }
    }

    /**
     * Retrieve all the Reservation from the database.
     * @param none
     * @return an array with all the reservation from the database
     */
    function select_all()
    {  
        $reservations = array();

        $request = "SELECT * FROM reservation;";

        foreach ($this->db->query($request) as $row)
        {
            $x = new Models\Reservation();

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
     * @param the reservation context
     * @return none
     */
    function insert($reservation)
    {
        $reservation->calculate_amount();

        $encoded_persons = base64_encode(serialize($reservation->persons));

        $request = "INSERT INTO reservation
                    SET price=$reservation->price,
                        insurance=$reservation->insurance,
                        destination='$reservation->destination',
                        nbr_persons=$reservation->personsCounter,
                        persons='".$encoded_persons."';";

        if ($this->db->exec($request) == 0)
            $reservation->append_warning("Rien n'a été enregistré.\n");
    }

    /**
     * Update the reservation in the database.
     * @param the reservation context
     * @return none
     */
    function update($reservation)
    {
        $reservation->calculate_amount();

        $encoded_persons = base64_encode(serialize($reservation->persons));

        $request = "UPDATE reservation
                    SET price=$reservation->price,
                        insurance=$reservation->insurance,
                        destination='$reservation->destination',
                        nbr_persons=$reservation->personsCounter,
                        persons='".$encoded_persons."'
                    WHERE id=$reservation->id;";

        if ($this->db->exec($request) == 0)
            $reservation->append_warning("Aucune données mise à jour.\n");
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