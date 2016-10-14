<?php

// Initialisation d'une session.
session_start();

// Déclarations et assignations des templates
GLOBAL $TPL_HOME, $TPL_DETAILS;

/* Les templates sont stockés dans des variables par la technique des `heredoc`
 * car cette technique protège de la corruption du texte tout en autorisant les
 * caractères d'échappement.
 * Deux formes de marqueur sont présent : %variable% et {$_POST['xxx']}
 * %variable% sert de marqueur à str_replace() et {$_POST['xxx']} est automatiquement
 * inséré par php si la variable existe.
 */
$TPL_HOME = <<<EOT
    <h2>Reservation</h2>
    <p>Le prix de la place est de 10€ jusqu'à 12 ans et ensuite de 15€.</p>
    <p>Le prix de l'assurance annulation est de 20€ quel ue soit le nombre de voyageurs.</p>

    <form method="post">
    <fieldset>
        <table>
            <tr>
                <th>Destination</th>
                <th><input type="text" name="destination" value="{$_POST['destination']}"></th>
            </tr>
            <tr>
                <th>Nombre de places</th>
                <th><input type="text" name="persons_counter" value="{$_POST['persons_counter']}"></th>
            </tr>
            <tr>
                <th>Assurance annulation</th>
                <th><input type="checkbox" name="insurance" value="checked" {$_POST['insurance']}></th>
            </tr>
            <tr>
                <th><button type="submit">Étape suivante</button></th>
                <th><button type="submit" name="reset">Annuler la réservation</button></th>
            </tr>
        </table>
        </fieldset>
    </form>
EOT;

$TPL_DETAILS = <<<EOT
    <h2>Détail des réservations</h2>

    <form method="post">
    <fieldset>
        <table>
            %table%
        </table>
    </fieldset>
    </form>
EOT;

/**
 * Vérifie la validité des données transmises.
 * @param none
 * @return true si les données sont valides, false autrement.
 */
function checkForInput()
{
    // Les paramètres existes ?
    if (isset($_POST['destination']) AND isset($_POST['persons_counter']))
    {
        // Les champs ne sont pas vides ?
        if ($_POST['destination'] AND $_POST['persons_counter'])
        {
            $_SESSION['destination'] = $_POST['destination'];
            $_SESSION['persons_counter'] = $_POST['persons_counter'];
            $_SESSION['insurance'] = isset($_POST['insurance']) ? true : false;

            return true;
        }
        else
        {
            echo "Veuillez remplir tout les champs correctement.";
        }
    }

    return false;
}

/**
 * Complète le nombre de case nécessaires au tableau des détails
 * et affiche la page générée.
 * @param none
 * @return none
 */
function goDetails()
{
    GLOBAL $TPL_DETAILS;

    $table = "";

    for ($i = 0; $i < intval($_SESSION['persons_counter']); $i++)
    {
        $table .= "<tr>" .
                  "  <th>Nom</th>" .
                  "  <th><input type=\"text\" name=\"fullname\"></th>" .
                  "</tr>" .
                  "<tr>" .
                  "  <th>Age</th>" .
                  "  <th><input type=\"text\" name=\"age\"></th>" .
                  "</tr>";
    }

    $TPL_DETAILS = str_replace("%table%", $table, $TPL_DETAILS);

    print($TPL_DETAILS);
}


function main()
{
    GLOBAL $TPL_HOME;

    // Si l'utilisateur annule sa réservation, la session est supprimée
    // et la page d'accueil est affichée.
    if (isset($_POST['reset']))
    {
        session_unset();
        print($TPL_HOME);
    }
    else if (checkForInput())
    {
        goDetails();
    }
    else
    {
        // page d'accueil
        print($TPL_HOME);
    }

    return 0;
}

main();

?>
