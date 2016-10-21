<?php

function vw_home()
{
	print_header();
	$tpl_home = file_get_contents('./templates/home.html');
	generate_home($tpl_home);
	print_footer();
}

function vw_details()
{
	print_header();
	$tpl_details = file_get_contents('./templates/details.html');
	generate_details($tpl_details);
	print_footer();
}

function vw_validation()
{
	print_header();
	$tpl_validation = file_get_contents('./templates/validation.html');
	generate_validation($tpl_validation);
	print_footer();
}

function vw_confirmation()
{
	print_header();
	$tpl_confirmation = file_get_contents('./templates/confirmation.html');
	generate_confirmation($tpl_confirmation);
	print_footer();
}

function print_header()
{
	print(file_get_contents('./templates/header.html') or die());
}

function print_footer()
{
	print(file_get_contents('./templates/footer.html') or die());
}

/**
 * Génère la page d'accueil et l'affiche.
 * @param none
 * @return none
 */
function generate_home($template)
{
    print($template);
}

/**
 * Complète le nombre de case nécessaires au tableau des détails
 * et affiche la page générée.
 * @param none
 * @return none
 */
function generate_details($template)
{
    $table = "";

    for ($i = 0; $i < intval($_SESSION['persons_counter']); $i++)
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
 * Génère la page récapitulative de la réservation et l'affiche.
 * @param none
 * @return none
 */
function generate_validation($template)
{
    //TODO
}

/**
 * Génère la page de confirmation en indiquant le prix à payer et l'affiche.
 * @param none
 * @return none
 */
function generate_confirmation($template)
{
    //TODO
}

?>
