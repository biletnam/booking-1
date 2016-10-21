<?php

// Initialisation d'une session.
session_start();

// Quick and Dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

require('controllers.php');

/* Si l'utilisateur annule sa réservation, la session est supprimée et la page d'accueil est
 * affichée. La méthode `header('Location:#')` peut sembler brutale mais c'est la seule
 * protégeant d'un rafraichissement de la page.
 */
if (isset($_POST['reset']))
{
    session_unset();
    session_destroy();
    session_regenerate_id(true);
    header('Location:#');
}

switch (isset($_POST['page']) ? $_POST['page'] : '1') {
    case '4':
    	ctr_confirmation();
        break;

    case '3':
    	ctr_validation();
        break;

    case '2':
    	ctr_details();
    	break;

    case '1':
    default:
        ctr_home();
        break;
}

return 0;

?>
