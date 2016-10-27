<?php

// Initialize a session.
session_start();

// Quick and Dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

require('controllers.php');

/* If the user cancels its reservation, the session is deleted and the homepage is displayed.
 * The method `header('Location:#')` may seem harsch but it's the only one refresh-proof.
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
        ctr_details(false);
    	break;

    case '1':
    default:
        ctr_home();
        break;
}

return 0;

?>
