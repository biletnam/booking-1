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
	 * @param
	 * @return
	 */
	function get_destination()
	{
		return $this->destination;
	}

	/**
	 * @param
	 * @return
	 */
	function set_destination($val)
	{
		$this->destination = $val;
	}

	/**
	 * @param
	 * @return
	 */
	function get_insurance()
	{
		return $this->insurance;
	}

	/**
	 * @param
	 * @return
	 */
	function set_insurance($val)
	{
		$this->insurance = $val;	
	}

	/**
	 * @param
	 * @return
	 */
	function get_persons_counter()
	{
		return $this->personsCounter;
	}

	/**
	 * @param
	 * @return
	 */
	function set_persons_counter($val)
	{
		$this->personsCounter = $val;
	}
}

?>
