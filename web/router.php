<?php

require_once 'controllers.php';

/**
 * Redirect the user request to the correct views but ensures
 * first that every informations required has been correctly
 * filled if needed. Otherwise, redirect to the form.
 * @param the application context (reservation + db)
 * @param the name of page to be displayed
 * @return none
 */
function route(&$ctx, $redirection)
{
    $fcts = array(

        'home' => function(&$ctx, $redirection)
        {
            /* nothing to do here */
            vw_display($ctx, $redirection);
        },

        'details' => function(&$ctx, $redirection)
        {
            // if inputs are incorrects, go home
            if (!controller_validateHome($ctx))
                $redirection = 'home';
            vw_display($ctx, $redirection);
        },

        'validation' => function(&$ctx, $redirection)
        {
            // if inputs are incorrects, go details
            if (!controller_validateDetails($ctx))
                $redirection = 'details';
            vw_display($ctx, $redirection);
        },

        'confirmation' => function(&$ctx, $redirection)
        {
            // if reservation is not completed, go home
            if (!controller_validateConfirmation($ctx))
            {
                $redirection = 'home';
            }
            else if ($ctx['isAdmin'])
            {
                $ctx['database']->update($ctx);
                $redirection = 'update';
            }
            else
            {
                $ctx['database']->insert($ctx);
            }

            vw_display($ctx, $redirection);
        },

        'admin' => function(&$ctx, $redirection)
        {
            if (isset($_GET['action']))
            {
                $id = intval($_GET['id']);

                if ($_GET['action'] == 'del')
                {
                    $ctx['database']->delete($ctx, $id);
                }
                else /* action = edit */
                {
                    // the edition will be processed on the creation form
                    $_ = $ctx['database']->selectOne($ctx, $id);
                    $ctx['reservation'] = $_;
                    $redirection = 'home';
                }
            }

            vw_display($ctx, $redirection);
        },

        '403' => function(&$ctx, $redirection) // forbidden
        {
            /* nothing to do here */
            vw_display($ctx, $redirection);
        },

        '404' => function(&$ctx, $redirection) // page not found
        {
            /* nothing to do here */
            vw_display($ctx, $redirection);
        }
    );

    if (!array_key_exists($redirection, $fcts))
        $redirection = '404';

    call_user_func_array($fcts[$redirection], array(&$ctx, $redirection));
}

?>
