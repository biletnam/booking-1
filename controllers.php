<?php

require('views.php');

use Models\Person as Person;
use Models\Reservation as Reservation;

/**
 * Redirect the user request to the correct views but ensures first that every information
 * required has been correctly filled is needed.
 * @param the reservation context
 * @param the name of page to be displayed
 * @return none
 */
function redirect_control($reservation, $redirection)
{
    switch ($redirection) {
        case 'home':
            vw_display($reservation, $redirection);
            break;

        case 'details':
            if (check_form_home($reservation))
                vw_display($reservation, $redirection);
            else
                ctr_home($reservation);
            break;

        case 'validation':
            if (check_form_details($reservation))
                vw_display($reservation, $redirection);
            else
                ctr_details($reservation);
            break;

        case 'confirmation':
            vw_display($reservation, $redirection);
            break;

        default:
            // how did you get here?
            var_dump($redirection);
    }
}

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
        $reservation->personsCounter = intval($_POST['personsCounter']);
        $reservation->insurance = isset($_POST['insurance']);

        // don't forget to save!! (-_-;)
        $reservation->save();

        return true;
    }

    // or the variables just exist ?
    elseif (isset($_POST['destination']) AND isset($_POST['personsCounter']))
    {
        print("Veuillez remplir tout les champs correctement.\n");
    }

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
        $ages = $_POST['ages'];
        $fullnames = $_POST['fullnames'];
        $persons = array();
        $count = count($fullnames);

        for ($i = 0; $i < $count; $i++)
        {
            // age is not 0 and fullname is set
            if ($ages[$i] AND $fullnames[$i])
                array_push($persons, new Person($fullnames[$i], $ages[$i]));
            else
                print("Veuillez remplir le champs ".$i." correctement.\n");
        }

        $reservation->persons = $persons;
        $reservation->save();

        // check if every fullname+age has an object in $persons
        return $count == count($persons);
    }

    // or the variables just exist ?
    elseif (isset($_POST['fullname']) AND isset($_POST['age']))
    {
        print("Veuillez remplir tous les champs correctement.\n");
    }

    return false;
}

?>
