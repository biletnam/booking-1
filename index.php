<?php

// Initialisation d'une session.
session_start();

// Déclaration des templates
GLOBAL $_TPL_HOME, $_TPL_DETAILS;

// Assignation des tamples
$TPL_HOME = <<<EOT
	<h2>Reservation</h2>
	<p>Le prix de la place est de 10€ jusqu'à 12 ans et ensuite de 15€.</p>
	<p>Le prix de l'assurance annulation est de 20€ quel ue soit le nombre de voyageurs.</p>

	<form method="get">
	<fieldset>
		<table>
			<tr>
				<th>Destination</th>
				<th><input type="text" name="destination" value="{$_GET['destination']}"></th>
			</tr>
			<tr>
				<th>Nombre de places</th>
				<th><input type="text" name="persons_counter" value="{$_GET['persons_counter']}"></th>
			</tr>
			<tr>
				<th>Assurance annulation</th>
				<th><input type="checkbox" name="insurance" value="checked" {$_GET['insurance']}></th>
			</tr>
			<tr>
				<th><button type="submit">Étape suivante</button></th>
				<th><button type="submit" name="cancelled">Annuler la réservation</button></th>
			</tr>
		</table>
		</fieldset>
	</form>
EOT;

$TPL_DETAILS = <<<EOT
	<h2>Détail des réservations</h2>

	<table>
		<tr>
			<th>Nom</th>
			<th>><input type="text" name="name"></th>
		</tr>
		<tr>
			<th>Age</th>
			<th>><input type="text" name="age"></th>
		</tr>
	</table>
EOT;

function checkForInput()
{
	/* Vérification sur les paramètres ont été passés
	 * dans l'url.
	 */
	if (isset($_GET['destination']) AND	isset($_GET['persons_counter']) AND
		isset($_GET['insurance']))
	{
		// S'ils ne sont pas VIDES !
		if ($_GET['destination'] AND $_GET['persons_counter'] AND $_GET['insurance'])
		{
			$_SESSION['destination'] = $_GET['destination'];
			$_SESSION['persons_counter'] = $_GET['persons_counter'];
			$_SESSION['insurance'] = $_GET['insurance'];

			return true;
		}
		else
		{
			echo "Veuillez remplir tout les champs";
		}
	}

	return false;
}

function main()
{
	GLOBAL $TPL_HOME;
	/* Si l'utilisateur annule sa réservation, la session est supprimée
	 * et l'utilisateur est renvoyé vers l'acceuil sans paramètres passés.
	 */
	if (isset($_GET['cancelled']))
	{
		unset($_SESSION['destination']);
		unset($_SESSION['persons_counter']);
		unset($_SESSION['insurance']);
		header('Location: index.php');
	}

	if (checkForInput())
		goDetails();

	// Affichage par défaut
	print($TPL_HOME);

	return 0;
}

main();

?>