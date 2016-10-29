<?php

require('views.php');

use Models\Person as Person;
use Models\Reservation as Reservation;

/**
 * Redirect the user request to the correct views but ensures first that every informations
 * required has been correctly filled if needed. Otherwise, redirect to the form.
 * @param the reservation context
 * @param the name of page to be displayed
 * @return none
 */
function redirect_control($reservation, $redirection)
{
    $fcts = array(
        'home' => function($reservation, $redirection) {
            vw_display($reservation, $redirection);
        },

        'details' => function($reservation, $redirection) {
            if (!check_form_home($reservation)) // if the information are incorrect,
                $redirection = 'home';          // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'validation' => function($reservation, $redirection) {
            if (!check_form_details($reservation)) // if the information are incorrect,
                $redirection = 'details';          // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'confirmation' => function($reservation, $redirection) {
            vw_display($reservation, $redirection);
            $reservation->reset();
        }
    );

    call_user_func_array($fcts[$redirection], array($reservation, $redirection));
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
        $count     = count($fullnames);
        $persons   = array();

        for ($i = 0; $i < $count; $i++)
        {
            // age is not 0 and fullname is set
            if ($ages[$i] AND $fullnames[$i])
                array_push($persons, new Person($fullnames[$i], $ages[$i]));
            else
                $reservation->append_warning("Veuillez remplir le champs ".($i+1)." correctement.\n");
        }

        $reservation->persons = $persons;
        $reservation->save();

        // check if every fullname+age has an object in $persons
        return $count == count($persons);
    }

    $reservation->append_warning("Veuillez remplir tous les champs correctement.\n");

    return false;
}

?>
