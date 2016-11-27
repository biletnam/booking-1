<?php

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

    /**
     * Combine the attributes in a single string.
     * @param none
     * @return the stringified attributes of the object
     */
    public function __toString()
    {
        return $this->fullname." - ".$this->age." ans";
    }

}

?>
