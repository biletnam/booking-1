<?php

require_once 'config.php';

// quick and dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

session_start();

// create the application context
$ctx = array(
    "reservation" => null,
    "database"    => null
);

// include middleware
require_once 'web/router.php';

// if the session exists then resume
if (isset($_SESSION['reservation']))
    $ctx['reservation'] = unserialize($_SESSION['reservation']);
else
    $ctx['reservation'] = new Models\Reservation();

// acquire a new pool to the database
$ctx['database'] = new Database();

// if the user cancels its reservation, the reservation is reseted to default.
if (isset($_POST['reset']))
{
    $_GET['page'] = ($ctx['reservation']->editionMode ? 'admin':'home');
    $ctx['reservation']->reset();
}

// starter, call the router on the given url
if (!empty($_GET['page']))
    redirect_control($ctx, $_GET['page']);
else
    redirect_control($ctx, 'home');

return 0;

?>
