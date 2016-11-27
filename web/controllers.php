<?php

require('views.php');

use Models\Person as Person;
use Models\Reservation as Reservation;

/**
 * Check the data validity transmitted at the homepage.
 * @param the reservation context
 * @return true if the data exist and have the right datatype.
 */
function check_form_home($reservation)
{
    // variables exist *AND* are not empty
    if (!empty($_POST['destination']) AND !empty($_POST['personsCounter']))
    {
        $reservation->destination = htmlspecialchars($_POST['destination']);
        $reservation->insurance   = isset($_POST['insurance']) ? 'True':'False';
        $personsCounter           = intval($_POST['personsCounter']);

        // set bounds to the number of persons
        if (1 <= $personsCounter AND $personsCounter <= 30)
        {
            $reservation->personsCounter = $personsCounter;
            $reservation->save(); // don't forget to save!! (-_-;)

            return true;
        }
        else
        {
            $reservation->append_warning("Vous ne pouvez enregistrer ".
                                         "que entre 1 et 30 personnes.\n");
        }
    }

    //TO FIX
    if (count($reservation->persons) != 0)
        return true; // we're coming from the next page and the datas are corrects

    $reservation->append_warning("Veuillez remplir tous les champs correctement.\n");

    return false;
}

/**
 * Check the data validity transmitted at the detail page.
 * @param the reservation context
 * @return true if the data exist and have the right datatype.
 */
function check_form_details($reservation)
{
    // tables exist *AND* are not empty
    if (!empty($_POST['fullnames']) AND !empty($_POST['ages']))
    {
        $ages      = $_POST['ages'];
        $fullnames = $_POST['fullnames'];
        $persons   = array();

        for ($i = 0; $i < count($fullnames); $i++)
        {
            // age in [1;120] and fullname is set
            if (1 <= $ages[$i] AND $ages[$i] <= 120 AND $fullnames[$i])
            {
                array_push($persons, new Person($fullnames[$i], $ages[$i]));
            }
            else
            {
                $reservation->append_warning("Veuillez remplir le(s) ".
                                             "participant(s) correctement.\n");
                return false;
            }
        }

        $reservation->persons = $persons;
        $reservation->save();
        
        return true;
    }

    $reservation->append_warning("Veuillez remplir tous les champs correctement.\n");

    return false;
}

/**
 * Remove a reservation in the database.
 * @param the reservation context
 * @return none
 */
function reservation_remove($reservation)
{
    $id = intval($_GET['id']);

    $request = "DELETE FROM reservation WHERE id=".$id.";";

    try
    {
        $db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                       charset=UTF8', MYSQL_USER, MYSQL_PASS);

        if ($db->exec($request) == 0)
            $reservation->append_warning("L'identifiant de réservation n'existe pas\n");
        else
            $reservation->append_warning("La réservation ".$id." a été ".
                                         "supprimée avec succès.\n");
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}

/**
 * Retrieve a Reservation from the database.
 * @param the reservation context
 * @return none
 */
function reservation_edit($reservation)
{  
    $id = intval($_GET['id']);

    $request = "SELECT * FROM reservation WHERE id=".$id.";";

    try
    {
        $db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                       charset=UTF8', MYSQL_USER, MYSQL_PASS);

        foreach ($db->query($request) as $data)
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
    catch (Exception $e)
    {
        die($e->getMessage());
    }

}

/**
 * Save the reservation in the database.
 * @param the reservation context
 * @return none
 */
function save_in_db($reservation)
{
    // shoud probably not be here
    $reservation->calculate_amount();

    $request = "INSERT INTO reservation
                SET price=$reservation->price,
                    insurance=$reservation->insurance,
                    destination='$reservation->destination',
                    nbr_persons=$reservation->personsCounter,
                    persons='".base64_encode(serialize($reservation->persons))."';";

    try
    {
        $db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                       charset=UTF8', MYSQL_USER, MYSQL_PASS);

        if ($db->exec($request) == 0)
            $reservation->append_warning("Rien n'a été enregistré.\n");
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}

/**
 * Update the reservation in the database.
 * @param the reservation context
 * @return none
 */
function update_db($reservation)
{
    $reservation->calculate_amount();

    $request = "UPDATE reservation
                SET price=$reservation->price,
                    insurance=$reservation->insurance,
                    destination='$reservation->destination',
                    nbr_persons=$reservation->personsCounter,
                    persons='".base64_encode(serialize($reservation->persons))."'
                WHERE id=$reservation->id;";

    try
    {
        $db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                       charset=UTF8', MYSQL_USER, MYSQL_PASS);

        if ($db->exec($request) == 0)
            $reservation->append_warning("Aucune données mise à jour.\n");
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
}

?>
