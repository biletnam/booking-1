<?php

// Custom configuration
define('MYSQL_USER', 'root');
define('MYSQL_PASS', 'root');
define('MYSQL_HOST', 'localhost');
define('MYSQL_DB', 'booking');

// Initialize a session.
session_start();

// Quick and Dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

// init state of reservation
$reservation = null;

// include middleware
require('router.php');

// if the session exists then resume
if (isset($_SESSION['reservation']))
    $reservation = unserialize($_SESSION['reservation']);
else
    $reservation = new Models\Reservation();

// if the user cancels its reservation, the reservation is reseted to default.
if (isset($_POST['reset']))
{
    $reservation->reset();
    unset($_GET['page']);
}

// starter, call the router on the given url
if (!empty($_GET['page']))
    redirect_control($reservation, $_GET['page']);
else
    redirect_control($reservation, 'home');

return 0;

?>
