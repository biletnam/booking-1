<?php

session_start();

// create the application context
$ctx = array(
    "reservation" => null, /* the current reservation */
    "database"    => null, /* an open connection to the db */
    "warning"     => "",   /* a list of warning to display */
    "isAdmin"     => false /* follow admin privileges */
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

// check if the uri contains /admin/
$ctx['isAdmin'] = preg_match('/admin/', $_SERVER['REQUEST_URI']);

// if the user cancels its reservation,
// the reservation is reseted to default.
if (isset($_POST['reset']))
{
    $_GET['page'] = $ctx['isAdmin']? 'admin':'home';
    $ctx['reservation']->reset();
}

// call the router on the requested page
if (!empty($_GET['page']))
    route($ctx, $_GET['page']);
else
    route($ctx, 'home');

return 0;

?>
