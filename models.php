<?php

class Reservation
{
	$destination = "";
	$insurance = false;
	$personsCounter = 1;
	$persons = new array();

	function __construct($destination, $personsCounter, $insurance)
	{
		$this->destination = $destination;
		$this->persons_counter = $personsCounter;
		$this->insurance = $insurance;
	}

}

?>
