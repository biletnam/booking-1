<?php

// controllers package

require('views.php');

/*
 * function validation_home($reservation)
 * function validation_details($reservation)
 */
require('controllers/validation.php');

/*
 * function database_delete($reservation)
 * function database_select_one($reservation, $id)
 * function database_select_all()
 * function database_insert($reservation)
 * function database_update($reservation)
 */
require('controllers/database.php');

?>
