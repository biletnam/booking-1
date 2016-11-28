<?php

require('config.php');

// quick and dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

session_start();

// include middleware
require('web/router.php');

// if the session exists then resume
if (isset($_SESSION['reservation']))
    $reservation = unserialize($_SESSION['reservation']);
else
    $reservation = new Models\Reservation();

// if the user cancels its reservation, the reservation is reseted to default.
if (isset($_POST['reset']))
{
    $_GET['page'] = ($reservation->editionMode ? 'admin':'home');
    $reservation->reset();
}

// starter, call the router on the given url
if (!empty($_GET['page']))
    redirect_control($reservation, $_GET['page']);
else
    redirect_control($reservation, 'home');

return 0;

?>
