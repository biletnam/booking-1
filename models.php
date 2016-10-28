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
     * @param the name of the property to return
     * @return the value of the property
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
     * @param the name of the property to set
     * @return none
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
        $this->save();
    }

    /**
     * Save the current instance of Reservation to the session.
     * @param none
     * @return none
     */
    public function save()
    {
        $_SESSION['reservation'] = serialize($this);
    }
}

class Person
{
    private $fullname;
    private $age;

    function __construct($fullname, $age)
    {
        $this->fullname = $fullname;
        $this->age = $age;
    }

    /**
     * Generic getter
     * @param the name of the property to return
     * @return the value of the property
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
     * @param the name of the property to set
     * @return none
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
