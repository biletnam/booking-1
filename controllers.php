<?php

require('views.php');

use Models\Person as Person;
use Models\Reservation as Reservation;

/**
 * @param
 * @return
 */
function ctr_home($reservation)
{
    // not much to do here
    vw_home($reservation);
}

/**
 * @param
 * @return
 */
function ctr_details($reservation)
{
    if (check_form_home($reservation))
        vw_details($reservation);
    else
        ctr_home($reservation);
}

/**
 * @param
 * @return
 */
function ctr_validation($reservation)
{
    if (check_form_details($reservation))
        vw_validation($reservation);
    else
        ctr_details($reservation);
}

/**
 * @param
 * @return
 */
function ctr_confirmation($reservation)
{
    vw_confirmation($reservation);
}

/**
 * Check the data validity transmitted at the homepage.
 * @param none
 * @return true if the data exist and have the right datatype.
 */
function check_form_home($reservation)
{
    // variables exist *AND* are not empty
    if (!empty($_POST['destination']) AND !empty($_POST['personsCounter']))
    {
        $reservation->destination = htmlspecialchars($_POST['destination']);
        $reservation->personsCounter = intval($_POST['personsCounter']);
        $reservation->insurance = isset($_POST['insurance']);

        // don't forget to save!! (-_-;)
        $reservation->save();

        return true;
    }

    // or the variables just exist ?
    elseif (isset($_POST['destination']) AND isset($_POST['personsCounter']))
    {
        print("Veuillez remplir tout les champs correctement.");
    }

    return false;
}

/**
 * Check the data validity transmitted at the detail page.
 * @param none
 * @return true if the data exist and have the right datatype.
 */
function check_form_details($reservation)
{
    // tables exist *AND* are not empty
    if (!empty($_POST['fullnames']) AND !empty($_POST['ages']))
    {
        $fullnames = $_POST['fullnames'];
        $ages = $_POST['ages'];
        $validInput = true;
        $persons = array();

        for ($i = 0; $i < count($fullnames); $i++)
        {
            // age is not 0 and fullname is set
            if ($ages[$i] AND $fullnames[$i])
            {
                array_push($persons, new Person($fullnames[$i], $ages[$i]));
            }
            else
            {
                print("Veuillez remplir le champs ".$i." correctement.");
                $invalidInput = false;
            }
        }

        $reservation->persons = $persons;
        $reservation->save();

        return $validInput;
    }

    // or the variables just exist ?
    elseif (isset($_POST['fullname']) AND isset($_POST['age']))
    {
        print("Veuillez remplir tous les champs correctement.");
    }

    return false;
}

?>
