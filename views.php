<?php

require('models.php');

use Models\Reservation as Reservation;

/**
 * Print the header and footer and let a generate_* function
 * fills between them the page given in argument.
 * @param the reservation context
 * @param the name of the page to display
 * @return none
 */
function vw_display($reservation, $page)
{
    print(get_chunk('header'));
    $template = get_chunk($page);

    // this is a array of function (^з^)-☆
    $fcts =  array('home'         => 'generate_home',
                   'details'      => 'generate_details',
                   'validation'   => 'generate_validation',
                   'confirmation' => 'generate_confirmation');

    call_user_func($fcts[$page], $reservation, $template);

    print(get_chunk('footer'));
}

/**
 * Retrieve the content of a template file.
 * @param the filename without extension of the html file
 * @return the content of the html file
 */
function get_chunk($chunk)
{
    return file_get_contents('./templates/'.$chunk.'.html');
}

/**
 * Generate and show the homepage.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_home($reservation, $template)
{
    $markers = array('%destination%','%personsCounter%','%insurance%');
    $values  = array($reservation->destination,
                     $reservation->personsCounter,
                    !$reservation->insurance ?: 'checked');
    print(str_replace($markers, $values, $template));
}

/**
 * Generate and show the number of text field necessary for the details page.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_details($reservation, $template)
{
    $table = "";

    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        $x = $y = ""; // fullname and age

        if (isset($reservation->persons[$i]))
        {
            $x = $reservation->persons[$i]->fullname;
            $y = $reservation->persons[$i]->age;
        }

        $table .=<<<EOD
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

    print(str_replace('%table%', $table, $template));
}

/**
 * Generate and show the validation page.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_validation($reservation, $template)
{
    $tables = "";

    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        $x = $reservation->persons[$i]->fullname;
        $y = $reservation->persons[$i]->age;

        $tables .=<<<EOD
        <tr>
            <th>Nom</th>
            <th>$x</th>
        </tr>
        <tr>
            <th>Age</th>
            <th>$y</th>
        </tr>
EOD;
    }

    print(str_replace('%table%', $tables, $template));
}

/**
 * Generate and show the confirmation page by calculating the amount to pay.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_confirmation($reservation, $template)
{
    define('INSURANCE', 20);
    define('CHILD_PRICE', 10);
    define('ADULT_PRICE', 15);

    $amount = 0;

    if ($reservation->insurance)
        $amount += INSURANCE;

    $persons = $reservation->persons;
    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        if ($persons[$i]->age <= 12)
            $amount += CHILD_PRICE;
        else
            $amount += ADULT_PRICE;
    }

    $textfield = "<p>Merci de bien vouloir verser la somme de ".$amount."€ sur le compte 000-000000-00</p>";
    print(str_replace('%amount%', $textfield, $template));
}

?>
