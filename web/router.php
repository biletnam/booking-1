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
            if (!validation_home($reservation))    // if the informations are incorrect,
                $redirection = 'home';             // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'validation' => function($reservation, $redirection) {
            if (!validation_details($reservation)) // if the information are incorrect,
                $redirection = 'details';          // return to the previous page.
            vw_display($reservation, $redirection);
        },

        'confirmation' => function($reservation, $redirection) {
            if ($reservation->editionMode)
            {
                database_update($reservation);
                $redirection = 'update';
            }
            else
                database_insert($reservation);

            vw_display($reservation, $redirection);
        },

        'admin' => function($reservation, $redirection) {
            if (isset($_GET['action']))
            {
                if ($_GET['action'] == 'del')
                    database_delete($reservation);
                else // action = edit
                {    // process the edition on the creation form
                    database_select_one($reservation, intval($_GET['id']));
                    $redirection = 'home';
                }
            }
            vw_display($reservation, $redirection);
        },

        '403' => function($reservation, $redirection) {
            vw_display($reservation, $redirection); // forbidden
        },

        '404' => function($reservation, $redirection) {
            vw_display($reservation, $redirection); // page not found
        }
    );

    if (!array_key_exists($redirection, $fcts))
        $redirection = '404';

    call_user_func_array($fcts[$redirection], array($reservation, $redirection));
}

?>