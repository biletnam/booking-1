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
        $reservation->insurance = isset($_POST['insurance']);
        $personsCounter = intval($_POST['personsCounter']);

        // set bounds to the number of persons
        if (1 <= $personsCounter AND $personsCounter <= 30)
        {
            $reservation->personsCounter = $personsCounter;
            $reservation->save(); // don't forget to save!! (-_-;)

            return true;
        }
        else
        {
            $reservation->append_warning("Vous ne pouvez enregistrer que entre 1 et 30 personnes.\n");
        }
    }

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
                $reservation->append_warning("Veuillez remplir le(s) participant(s) correctement.\n");
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
 * Save the reservation in the database.
 * @param the reservation context
 * @return none
 */
function save_in_db($reservation)
{
    // php with its bullshit booleans
    $insurance = $reservation->insurance ? 'True' : 'False';

    try
    {
        $db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB.';
                        charset=utf8', MYSQL_USER, MYSQL_PASS);

        $request = "INSERT INTO reservation SET
                    destination='$reservation->destination',
                      insurance=$insurance,
                    nbr_persons='$reservation->personsCounter',
                        persons='".serialize($reservation->persons)."';";

        if ($db->exec($request) == 0)
            throw new Exception('Inconsistent row altered');
    }
    catch (Exception $e)
    {
        die('Error: '.$e->getMessage());
    }
}

?>
