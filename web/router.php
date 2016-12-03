<?php

require_once 'controllers.php';

/**
 * Redirect the user request to the correct views but ensures first that every
 * informations required has been correctly filled if needed. Otherwise, redirect
 * to the form.
 * @param the application context (reservation + db)
 * @param the name of page to be displayed
 * @return none
 */
function redirect_control($ctx, $redirection)
{
    $fcts = array(
        'home' => function($ctx, $redirection) {
            vw_display($ctx, $redirection);
        },

        'details' => function($ctx, $redirection) {
            if (!validation_home($ctx['reservation']))    // if the informations
                $redirection = 'home';                    // are incorrect, return
            vw_display($ctx, $redirection);               // to the previous page.
        },

        'validation' => function($ctx, $redirection) {
            if (!validation_details($ctx['reservation'])) // if the informations
                $redirection = 'details';                 // are incorrect, return
            vw_display($ctx, $redirection);               // to the previous page.
        },

        'confirmation' => function($ctx, $redirection) {
            if ($ctx['reservation']->editionMode)
            {
                $ctx['database']->update($ctx['reservation']);
                $redirection = 'update'; // go to the update confirmation page
            }
            else
                $ctx['database']->insert($ctx['reservation']);

            vw_display($ctx, $redirection);
        },

        'admin' => function($ctx, $redirection) {
            if (isset($_GET['action']))
            {
                if ($_GET['action'] == 'del')
                    $ctx['database']->delete($ctx['reservation']);
                else // action = edit
                {    // process the edition on the creation form
                    $ctx['database']->select_one($ctx['reservation']);
                    $redirection = 'home';
                }
            }
            vw_display($ctx, $redirection);
        },

        '403' => function($ctx, $redirection) {
            vw_display($ctx, $redirection); // forbidden
        },

        '404' => function($ctx, $redirection) {
            vw_display($ctx, $redirection); // page not found
        }
    );

    if (!array_key_exists($redirection, $fcts))
        $redirection = '404';

    call_user_func_array($fcts[$redirection], array($ctx, $redirection));
}

?>