<?php

// quick and dirty debugging
error_reporting(~0);
ini_set('display_errors', 1);

session_start();

// create the application context
$ctx = array(
    "reservation" => null, /* the current reservation */
    "database"    => null, /* an open connection to the db */
    "warning"     => "",   /* a list of warning to display */
);

// include config and middleware
require_once 'config.php';
require_once 'web/router.php';

// if the session exists then resume
if (isset($_SESSION['reservation']))
    $ctx['reservation'] = unserialize($_SESSION['reservation']);
else
    $ctx['reservation'] = new Reservation();

$ctx['database'] = new Database();

// if the user cancels its reservation, the reservation is reseted to default.
if (isset($_POST['reset']))
{
    $ctx['reservation']->reset();

    // if the url contains /admin/
    if (preg_match('/admin/', $_SERVER['REQUEST_URI']))
        $_GET['page'] = 'admin';
    else
        $_GET['page'] = 'home';
}

// starter, call the router on the given url
if (!empty($_GET['page']))
    redirect_control($ctx, $_GET['page']);
else
    redirect_control($ctx, 'home');

return 0;

?>
