<?php

class Reservation
{
	private $destination = "";
	private $insurance = false;
	private $personsCounter = 1;
	private $persons = array();

	function __construct($destination, $personsCounter, $insurance)
	{
		$this->destination = $destination;
		$this->personsCounter = $personsCounter;
		$this->insurance = $insurance;
	}

	/**
	 * Generic getter
	 * @param
	 * @return
	 */
	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->$property;
		}
	}

	/**
	 * Generic setter
	 * @param
	 * @return
	 */
	public function __set($property, $value)
	{
		if (property_exists($this, $property))
		{
			$this->$property = $value;
		}
	}

}

?>
