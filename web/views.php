<?php

require_once 'models.php';

/**
 * Print the header and footer and let a generate_* function
 * fills between them the page given in argument.
 * @param the application context (reservation + db)
 * @param the name of the page to display
 * @return none
 */
function vw_display($ctx, $page)
{
    echo get_chunk('header');
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

    call_user_func($fcts[$page], $ctx, $template);

    echo get_chunk('footer');
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
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function display_403($ctx, $template)
{
    echo $template;
}

function display_404($ctx, $template)
{
    echo $template;
}

/**
 * Generate and show the homepage.
 * Also show a warning banner if the data submitted was inadequate.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_home($ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div id="warning">'.$ctx['warning'].'</div>';

    $markers = array('%destination%','%personsCounter%','%insurance%',
                     '%redirect%'   ,'%warning%');
    $values  = array($reservation->destination,
                     $reservation->personsCounter,
                     $reservation->insurance == 'False' ?:'checked',
                     $ctx['isAdmin']? '../../../admin':'home',
                     $ctx['warning']);

    echo str_replace($markers, $values, $template);
}

/**
 * Generate and show the administration interface with
 * all the reservations in stored in the database.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_admin($ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div id="warning">'.$ctx['warning'].'</div>';

    $tables = '';
    
    foreach($ctx['database']->select_all() as $cell) // for every reservation
    {
        $persons = '';
        foreach($cell->persons as $_)                // for every person in
            $persons .= $_.'<br>';                   // the reservation

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
    $values  = array($tables, $ctx['warning']);

    echo str_replace($markers, $values, $template);

    $reservation->reset();
}

/**
 * Generate and show the administration update page with the new price.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_update($ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div id="warning">'.$ctx['warning'].'</div>';

    $reservation->calculate_amount();

    $markers = array('%amount%', '%warning%'); 
    $values  = array($reservation->price, $ctx['warning']);

    echo str_replace($markers, $values, $template);

    $reservation->reset();
}

/**
 * Generate and show the number of text field necessary for the details page.
 * Also show a warning banner if the data submitted was inadequate.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_details($ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div id="warning">'.$ctx['warning'].'</div>';

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
    $values  = array($tables, $ctx['warning']);

    echo str_replace($markers, $values, $template);
}

/**
 * Generate and show the validation page.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_validation($ctx, $template)
{
    $reservation = $ctx['reservation'];
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

    echo str_replace('%table%', $tables, $template);
}

/**
 * Generate and show the confirmation page.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function generate_confirmation($ctx, $template)
{
    $reservation = $ctx['reservation'];

    echo str_replace('%amount%', $reservation->price, $template);
    
    $reservation->reset();
}

?>
