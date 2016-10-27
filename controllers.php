<?php

require('views.php');

/**
 * @param
 * @return
 */
function ctr_home()
{
    // not much to do here
    vw_home();
}

/**
 * @param
 * @return
 */
function ctr_details($bypass)
{
    if (check_form_home() || $bypass)
        vw_details();
    else
        ctr_home();
}

/**
 * @param
 * @return
 */
function ctr_validation()
{
    if (check_form_details())
        vw_validation();
    else
        ctr_details(true);
}

/**
 * @param
 * @return
 */
function ctr_confirmation()
{
    vw_confirmation();
}

/**
 * Check the data validity transmitted at the homepage.
 * @param none
 * @return true if the data exist and have the right datatype.
 */
function check_form_home()
{
    // variables exist *AND* are not empty
    if (!empty($_POST['destination']) AND !empty($_POST['persons_counter']))
    {
        $_SESSION['destination'] = htmlspecialchars($_POST['destination']);
        $_SESSION['persons_counter'] = intval($_POST['persons_counter']);
        $_SESSION['insurance'] = isset($_POST['insurance']);

        return true;
    }

    // or the variables just exist ?
    elseif (isset($_POST['destination']) AND isset($_POST['persons_counter']))
    {
        print("Veuillez remplir tout les champs correctement.");
    }

    return false;
}

/**
 * Check the data validity transmitted at the detail page.
 * @param none
 * @return true if the data exist and have the right datatype.
 */
function check_form_details()
{
    // variables exist *AND* are not empty
    if (!empty($_POST['fullname']) AND !empty($_POST['age']))
    {

        //TODO

        return true;
    }

    // or the variables just exist ?
    elseif (isset($_POST['fullname']) AND isset($_POST['age']))
    {
        print("Veuillez remplir tout les champs correctement.");
    }

    return false;
}

?>
