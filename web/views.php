<?php

require_once 'models.php';

/**
 * Print the header and footer and let a generate_* function
 * fills between them the page given in argument.
 * @param the application context (reservation + db)
 * @param the name of the page to display
 * @return none
 */
function vw_display(&$ctx, $page)
{
    echo vw_getChunk('header');
    $template = vw_getChunk($page);

    // this is an array of functions (^з^)-☆
    $fcts = array('403'          => 'vw_page403',
                  '404'          => 'vw_page404',
                  'home'         => 'vw_pageHome',
                  'admin'        => 'vw_pageAdmin',
                  'update'       => 'vw_pageUpdate',
                  'details'      => 'vw_pageDetails',
                  'validation'   => 'vw_pageValidation',
                  'confirmation' => 'vw_pageConfirmation');

    call_user_func($fcts[$page], $ctx, $template);

    echo vw_getChunk('footer');
}

/**
 * Retrieve the content of a template file.
 * @param the filename without extension of the html file
 * @return the content of the html file
 */
function vw_getChunk($chunk)
{
    return file_get_contents('./templates/'.$chunk.'.html');
}

/**
 * Display the http error page.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function vw_page403(&$ctx, $template)
{
    echo $template;
}

function vw_page404(&$ctx, $template)
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
function vw_pageHome(&$ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div class="alert alert-danger" role="alert">'
                          .$ctx['warning'].'</div>';

    $markers = array('%destination%','%personsCounter%','%insurance%',
                     '%redirect%'   ,'%warning%'       ,'DOCUMENTROOT');
    $values  = array($reservation->destination,
                     $reservation->personsCounter,
                     $reservation->insurance == 'False' ?:'checked',
                     DOCUMENTROOT.($ctx['isAdmin']? 'admin':'home'),
                     $ctx['warning'],
                     DOCUMENTROOT);

    echo str_replace($markers, $values, $template);
}

/**
 * Generate and show the administration interface with
 * all the reservations in stored in the database.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function vw_pageAdmin(&$ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div class="alert alert-danger" role="alert">'
                          .$ctx['warning'].'</div>';

    $tables = '';
    
    foreach($ctx['database']->selectAll() as $cell) // for every reservation
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
            <th><a href="DOCUMENTROOT/admin/edit/$cell->id/">Edit</a></th>
            <th><a href="DOCUMENTROOT/admin/del/$cell->id/">Delete</a></th>
        </tr>
EOD;
    }

    $markers = array('%table%', '%warning%', 'DOCUMENTROOT');
    $values  = array($tables, $ctx['warning'], DOCUMENTROOT);

    echo str_replace($markers, $values, $template);

    $reservation->reset();
}

/**
 * Generate and show the administration update page with the new price.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function vw_pageUpdate(&$ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div class="alert alert-danger" role="alert">'
                          .$ctx['warning'].'</div>';

    $reservation->calculateAmount();

    $markers = array('%amount%', '%warning%', 'DOCUMENTROOT'); 
    $values  = array($reservation->price, $ctx['warning'], DOCUMENTROOT);

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
function vw_pageDetails(&$ctx, $template)
{
    $reservation = $ctx['reservation'];

    if ($ctx['warning'])
        $ctx['warning'] = '<div class="alert alert-danger" role="alert">'
                          .$ctx['warning'].'</div>';

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

    $markers = array('%table%', '%warning%', 'DOCUMENTROOT');
    $values  = array($tables, $ctx['warning'], DOCUMENTROOT);

    echo str_replace($markers, $values, $template);
}

/**
 * Generate and show the validation page.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function vw_pageValidation(&$ctx, $template)
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

    $markers = array('%table%', 'DOCUMENTROOT');
    $values  = array($tables, DOCUMENTROOT);

    echo str_replace($markers, $values, $template);
}

/**
 * Generate and show the confirmation page.
 * @param the application context (reservation + db)
 * @param the template content
 * @return none
 */
function vw_pageConfirmation(&$ctx, $template)
{
    $reservation = $ctx['reservation'];

    $reservation->calculateAmount();

    $markers = array('%amount%', 'DOCUMENTROOT');
    $values  = array($reservation->price, DOCUMENTROOT);

    echo str_replace($markers, $values, $template);
    
    $reservation->reset();
}

?>
