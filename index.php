<?php

/* Template du corps de la page d'accueil. */
$body_tpl = <<<EOT
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
				<th>**</th>
			</tr>
			<tr>
				<th><button type="submit">Étape suivante</button></th>
				<th><button type="submit" formaction="cancel.php">Annuler la réservation</button></th>
			</tr>
		</table>
		</fieldset>
	</form>
EOT;

$details_tpl = <<<EOT
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

if (isset($_GET['destination']) AND
	isset($_GET['persons_counter']))
{

	if ($_GET['destination'] AND
		$_GET['persons_counter'])
	{
		echo "Hello";
	}
	else
	{
		echo "Veuillez remplir tout les champs";
	}
}

print ($body_tpl);

?>