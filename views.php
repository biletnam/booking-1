<?php

require('models.php');

use Models\Reservation as Reservation;

/**
 * @param
 * @return
 */
function vw_display($reservation, $page)
{
    pr_chunk('header');
    $template = file_get_contents('./templates/'.$page.'.html');

    switch ($page) {
        case 'home':
            generate_home($reservation, $template);
            break;

        case 'details':
            generate_details($reservation, $template);
            break;

        case 'validation':
            generate_validation($reservation, $template);
            break;

        case 'confirmation':
            generate_confirmation($reservation, $template);
            break;
    }

    pr_chunk('footer');
}

/**
 * @param
 * @return
 */
function pr_chunk($chunk)
{
    print(file_get_contents('./templates/'.$chunk.'.html'));
}

/**
 * Generate and show the homepage.
 * @param none
 * @return none
 */
function generate_home($reservation, $template)
{
    $markers = array('%destination%','%personsCounter%','%insurance%');
    $values = array($reservation->destination,
                    $reservation->personsCounter,
                    $reservation->insurance ? 'checked' : '');
    print(str_replace($markers, $values, $template));
}

/**
 * Generate and show the number of text field necessary for the details page.
 * @param none
 * @return none
 */
function generate_details($reservation, $template)
{
    $tables = "";
    $x = $y = ""; // fullname and age

    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        if (isset($reservation->persons[$i]))
        {
            $x = $reservation->persons[$i]->fullname;
            $y = $reservation->persons[$i]->age;
        }
        else
        {
            $x = $y = "";
        }

        $tables .=<<<EOD
        <tr>
            <th>Nom</th>
            <th><input type="text" name="fullnames[]" value="$x"></th>
        </tr>
        <tr>
            <th>Age</th>
            <th><input type="text" name="ages[]" value="$y"></th>
        </tr>
EOD;
    }

    print(str_replace('%table%', $tables, $template));
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
