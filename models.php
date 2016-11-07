<?php

namespace Models;

class Reservation
{
    private $destination;
    private $insurance;
    private $personsCounter;
    private $persons;
    private $warning;

    function __construct()
    {
        $this->destination    = "";
        $this->personsCounter = 1;
        $this->insurance      = false;
        $this->persons        = array();
        $this->warning        = "";
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
        $this->destination    = "";
        $this->personsCounter = 1;
        $this->insurance      = false;
        $this->persons        = array();
        $this->warning        = "";
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

    /**
     * Append every warning message to a single string that will be used
     * to warn the user about inadequate informations.
     * @param the message to append to warnings
     * @return none
     */
    public function append_warning($message)
    {
        $this->warning .= $message;
    }

    /**
     * Clean the warning string and save it.
     * @param none
     * @return none
     */
    public function reset_warning()
    {
        $this->warning = "";
        $this->save();
    }
}

class Person
{
    private $fullname;
    private $age;

    function __construct($fullname, $age)
    {
        $this->fullname = htmlspecialchars($fullname);
        $this->age      = intval($age);
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
