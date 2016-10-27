<?php

// Initialize a session.
session_start();

// Quick and Dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

// Init state of reservation
$reservation = null;

require('controllers.php');

// If the user cancels its reservation, the session
// is deleted and the homepage is displayed.
if (isset($_POST['reset']))
{
    session_unset();
    session_destroy();
    session_regenerate_id(true);
}
// or resume the current session
elseif (isset($_SESSION['reservation']))
{
    $reservation = unserialize($_SESSION['reservation']);
}
else
{
    $reservation = new Models\Reservation();
}

switch (isset($_POST['page']) ? $_POST['page'] : '1') {
    case '4':
    	ctr_confirmation($reservation);
        break;

    case '3':
    	ctr_validation($reservation);
        break;

    case '2':
        ctr_details($reservation);
    	break;

    case '1':
    default:
        ctr_home($reservation);
        break;
}

return 0;

?>
