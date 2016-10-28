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

// if the session exists then resume
if (isset($_SESSION['reservation']))
    $reservation = unserialize($_SESSION['reservation']);
else
    $reservation = new Models\Reservation();

// if the user cancels its reservation, the reservation is reseted to default.
if (isset($_POST['reset']))
    $reservation->reset();

// starter, redirect the instruction to the adequate controller
if (!empty($_POST['page']))
    redirect_control($reservation, $_POST['page']);
else
    redirect_control($reservation, 'home');

return 0;

?>
