<?php

require('models.php');

use Models\Reservation as Reservation;

/**
 * @param
 * @return
 */
function vw_home($reservation)
{
	print_header();
	$tpl_home = file_get_contents('./templates/home.html');
	generate_home($reservation, $tpl_home);
	print_footer();
}

/**
 * @param
 * @return
 */
function vw_details($reservation)
{
	print_header();
	$tpl_details = file_get_contents('./templates/details.html');
	generate_details($reservation, $tpl_details);
	print_footer();
}

/**
 * @param
 * @return
 */
function vw_validation($reservation)
{
	print_header();
	$tpl_validation = file_get_contents('./templates/validation.html');
	generate_validation($reservation, $tpl_validation);
	print_footer();
}

/**
 * @param
 * @return
 */
function vw_confirmation($reservation)
{
	print_header();
	$tpl_confirmation = file_get_contents('./templates/confirmation.html');
	generate_confirmation($reservation, $tpl_confirmation);
	print_footer();
}

/**
 * @param
 * @return
 */
function print_header()
{
	print(file_get_contents('./templates/header.html'));
}

/**
 * @param
 * @return
 */
function print_footer()
{
	print(file_get_contents('./templates/footer.html'));
}

/**
 * Generate and show the homepage.
 * @param none
 * @return none
 */
function generate_home($reservation, $template)
{
	$markers = array('%destination%','%personsCounter%','%insurance%');
	$values = array('','','');
    print(str_replace($markers, $values, $template));
}

/**
 * Generate and show the number of text field necessary for the details page.
 * @param none
 * @return none
 */
function generate_details($reservation, $template)
{
    $table = "";

    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        $table .=<<<EOD
        <tr>
            <th>Nom</th>
            <th><input type="text" name="fullname[]"></th>
        </tr>
        <tr>
            <th>Age</th>
            <th><input type="text" name="age[]"></th>
        </tr>
EOD;
    }

    $template = str_replace('%table%', $table, $template);

    print($template);
}

/**
 * Generate and show the validation page.
 * @param none
 * @return none
 */
function generate_validation($reservation, $template)
{
    //TODO
}

/**
 * Generate and show the confirmation page by calculating the amount to pay.
 * @param none
 * @return none
 */
function generate_confirmation($reservation, $template)
{
    //TODO
}

?>
