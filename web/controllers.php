<?php

// controllers package

require('views.php');

use Models\Person as Person;
use Models\Reservation as Reservation;

/*
 * function check_form_home($reservation)
 * function check_form_details($reservation)
 */
require('controllers/validation.php');

/*
 * function reservation_remove($reservation)
 * function reservation_edit($reservation)
 * function save_in_db($reservation)
 * function update_db($reservation)
 */
require('controllers/database.php');

?>
