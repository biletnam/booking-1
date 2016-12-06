<?php

require_once 'views.php';

/**
 * Check the data validity transmitted at the homepage.
 * @param the application context (reservation + db)
 * @return true if the data exist and have the right datatype.
 */
function ctr_validateHome(&$ctx)
{
    $reservation = $ctx['reservation'];

    // variables exist *AND* are not empty
    if (!empty($_POST['destination']) AND !empty($_POST['personsCounter']))
    {
        $reservation->destination = htmlspecialchars($_POST['destination']);
        $reservation->insurance   = isset($_POST['insurance'])? 'True':'False';
        $personsCounter           = intval($_POST['personsCounter']);

        // set bounds to the number of persons
        if (1 <= $personsCounter AND $personsCounter <= 30)
        {
            $reservation->personsCounter = $personsCounter;
            $reservation->save();

            return true;
        }

        $ctx['warning'] .= "Vous ne pouvez enregistrer ".
                           "que entre 1 et 30 personnes.\n";
    }

    // Bypass checks if datas were already filled
    if ($reservation->destination)
        return true;

    $ctx['warning'] .= "Veuillez remplir tous les champs correctement.\n";

    return false;
}

/**
 * Check the data validity transmitted at the detail page.
 * @param the application context (reservation + db)
 * @return true if the data exist and have the right datatype.
 */
function ctr_validateDetails(&$ctx)
{
    $reservation = $ctx['reservation'];

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
                $ctx['warning'] .= "Veuillez remplir le(s) ".
                                   "participant(s) correctement.\n";
                return false;
            }
        }

        $reservation->persons = $persons;
        $reservation->save();
        
        return true;
    }

    // Bypass checks if datas were already filled
    if ($reservation->persons)
        return true;

    $ctx['warning'] .= "Veuillez remplir tous les champs correctement.\n";

    return false;
}

/**
 * Ensure every fields in the reservation context has been filled.
 * @param the application context (reservation + db)
 * @return true if every fields are ok
 */
function ctr_validateConfirmation(&$ctx)
{
    $reservation = $ctx['reservation'];

    if ($reservation->persons AND $reservation->destination)
        return true;

    $ctx['warning'] .= "Please, do not play with the URL.\n";

    return false;
}

?>
