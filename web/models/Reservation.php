<?php

class Reservation
{
    private $id;
    private $destination;
    private $insurance;
    private $personsCounter;
    private $persons;
    private $price;

    function __construct()
    {
        $this->id             = 0; // only useful in edition mode
        $this->destination    = '';
        $this->personsCounter = 1;
        $this->insurance      = 'False'; // a string because of php bullshit
        $this->persons        = array();
        $this->price          = 0;
    }

    /**
     * Restore all the properties by default.
     * This method is useful to avoid the 'Trying to get property of non-object' error.
     * @param none
     * @return none
     */
    public function reset()
    {
        $this->id             = 0;
        $this->destination    = '';
        $this->personsCounter = 1;
        $this->insurance      = 'False';
        $this->persons        = array();
        $this->price          = 0;
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
     * Calculate the amount to pay with the number of persons and their age.
     * @param none
     * @return none
     */
    public function calculateAmount()
    {
        $amount = 0;

        if ($this->insurance == 'True')
            $amount += INSURANCE_PRICE;

        foreach ($this->persons as $person)
        {
            if ($person->age <= 12)
                $amount += CHILD_PRICE;
            else
                $amount += ADULT_PRICE;
        }

        $this->price = $amount;
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
     * @param the new value to set
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
