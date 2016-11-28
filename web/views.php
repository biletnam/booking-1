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

    // this is an array of functions (^з^)-☆
    $fcts = array('403'          => 'display_403',
                  '404'          => 'display_404',
                  'home'         => 'generate_home',
                  'admin'        => 'generate_admin',
                  'update'       => 'generate_update',
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
 * Display the http error page.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function display_403($reservation, $template)
{
    print($template);
}

function display_404($reservation, $template)
{
    print($template);
}

/**
 * Generate and show the homepage.
 * Also show a warning banner if the data submitted was inadequate.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_home($reservation, $template)
{
    if ($reservation->warning)
        $reservation->warning = '<div id="warning">'.$reservation->warning.'</div>';

    $markers = array('%destination%','%personsCounter%','%insurance%',
                     '%redirect%'   ,'%warning%');
    $values  = array($reservation->destination,
                     $reservation->personsCounter,
                     $reservation->insurance == 'False' ?:'checked',
                     $reservation->editionMode ? '../../../admin':'home',
                     $reservation->warning);

    print(str_replace($markers, $values, $template));

    $reservation->reset_warning();
}

/**
 * Generate and show the administration interface with
 * all the reservations in stored in the database.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_admin($reservation, $template)
{
    if ($reservation->warning)
        $reservation->warning = '<div id="warning">'.$reservation->warning.'</div>';

    $tables = '';
    
    foreach(database_select_all() as $cell) // for every reservation
    {
        $persons = '';
        foreach($cell->persons as $p)       // for every person in the reservation
            $persons .= $p.'<br>';

        $tables .=<<<EOD
        <tr>
            <th>$cell->id</th>
            <th>$cell->destination</th>
            <th>$cell->insurance</th>
            <th>$cell->personsCounter</th>
            <th>$cell->price</th>
            <th>$persons</th>
            <th><a href="admin/edit/$cell->id/">Edit</a></th>
            <th><a href="admin/del/$cell->id/">Delete</a></th>
        </tr>
EOD;
    }

    $markers = array('%table%', '%warning%');
    $values  = array($tables, $reservation->warning);

    print(str_replace($markers, $values, $template));

    $reservation->reset_warning();
    $reservation->reset();
}

/**
 * Generate and show the administration update page with the new price.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_update($reservation, $template)
{
    if ($reservation->warning)
        $reservation->warning = '<div id="warning">'.$reservation->warning.'</div>';

    $reservation->calculate_amount();

    $markers = array('%amount%', '%warning%'); 
    $values = array($reservation->price, $reservation->warning);

    print(str_replace($markers, $values, $template));

    $reservation->reset_warning();
    $reservation->reset();
}

/**
 * Generate and show the number of text field necessary for the details page.
 * Also show a warning banner if the data submitted was inadequate.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_details($reservation, $template)
{
    if ($reservation->warning)
        $reservation->warning = '<div id="warning">'.$reservation->warning.'</div>';

    $tables = '';

    for ($i = 0; $i < $reservation->personsCounter; $i++)
    {
        $x = $y = ""; // fullname and age

        if (isset($reservation->persons[$i]))
        {
            $x = $reservation->persons[$i]->fullname;
            $y = $reservation->persons[$i]->age;
        }

        $tables .=<<<EOD
        <tr>
            <th>Nom</th>
            <th><input type="text" name="fullnames[]" value="$x" required></th>
        </tr>
        <tr>
            <th>Age</th>
            <th><input type="number" name="ages[]" value="$y" min="1" max="120" required></th>
        </tr>
EOD;
    }

    $markers = array('%table%', '%warning%');
    $values  = array($tables, $reservation->warning);

    print(str_replace($markers, $values, $template));

    $reservation->reset_warning();
}

/**
 * Generate and show the validation page.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_validation($reservation, $template)
{
    $tables = '';

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
 * Generate and show the confirmation page.
 * @param the reservation context
 * @param the template content
 * @return none
 */
function generate_confirmation($reservation, $template)
{
    print(str_replace('%amount%', $reservation->price, $template));
    
    $reservation->reset();
}

?>
