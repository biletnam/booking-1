<?php

namespace Models;

class Reservation
{
    private $destination;
    private $insurance;
    private $personsCounter;
    private $persons;

    function __construct()
    {
        $this->destination = "";
        $this->personsCounter = 1;
        $this->insurance = false;
        $this->persons = array();
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

    /**
     * Restore all the properties by default.
     * This method is useful to avoid the 'Trying to get property of non-object' error.
     * @param none
     * @return none
     */
    public function reset()
    {
        $this->destination = "";
        $this->personsCounter = 1;
        $this->insurance = false;
        $this->persons = array();
    }

}

?>
