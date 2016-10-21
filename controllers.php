<?php

require('views.php');

function ctr_home()
{
	vw_home();
}

function ctr_details()
{
	vw_details();
}

function ctr_validation()
{
	vw_validation();
}

function ctr_confirmation()
{
	vw_confirmation();
}

/**
 * Vérifie la validité des données transmises à la page d'accueil.
 * @param none
 * @return true si les données sont valides, false autrement.
 */
function check_form_home()
{
    // les variables existent *ET* ne sont pas vides ?
    if (!empty($_POST['destination']) AND !empty($_POST['persons_counter']))
    {
        $_SESSION['destination'] = htmlspecialchars($_POST['destination']);
        $_SESSION['persons_counter'] = intval($_POST['persons_counter']);
        $_SESSION['insurance'] = isset($_POST['insurance']);

        return true;
    }

    // ou les variables existent simplement ?
    elseif (isset($_POST['destination']) AND isset($_POST['persons_counter']))
    {
        print("Veuillez remplir tout les champs correctement.");
    }

    return false;
}

/**
 * Vérifie la validité des données transmises à la page de détails.
 * @param none
 * @return true si les données sont valides, false autrement.
 */
function check_form_details()
{
    // les variables existent *ET* ne sont pas vides ?
    if (!empty($_POST['fullname']) AND !empty($_POST['age']))
    {

    	//TODO

        return true;
    }

    // ou les variables existent simplement ?
    elseif (isset($_POST['fullname']) AND isset($_POST['age']))
    {
        print("Veuillez remplir tout les champs correctement.");
    }

    return false;
}

?>
