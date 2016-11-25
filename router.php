<?php

require('controllers.php');

/**
 * Redirect the user request to the correct views but ensures first that every informations
 * required has been correctly filled if needed. Otherwise, redirect to the form.
 * @param the reservation context
 * @param the name of page to be displayed
 * @return none
 */
function redirect_control($reservation, $redirection)
{
    $fcts = array(
        'home' => function($reservation, $redirection) {
            vw_display($reservation, $redirection);
        },

        'details' => function($reservation, $redirection) {
            if (!check_form_home($reservation))    // if the informations are incorrect,
                $redirection = 'home';             // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'validation' => function($reservation, $redirection) {
            if (!check_form_details($reservation)) // if the information are incorrect,
                $redirection = 'details';          // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'confirmation' => function($reservation, $redirection) {
            save_in_db($reservation);
            vw_display($reservation, $redirection);
            $reservation->reset();
        },

        '404' => function($reservation, $redirection) {
            vw_display($reservation, $redirection); // 404 — page not found
        }
    );

    if (!array_key_exists($redirection, $fcts))
        $redirection = '404';

    call_user_func_array($fcts[$redirection], array($reservation, $redirection));
}

?>