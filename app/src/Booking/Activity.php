<?php declare(strict_types = 1);

namespace App\Booking;

class Activity
{
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?string $date;
    private ?string $time;
    private ?float $cost;
    private ?int $vendoruser_id;
    private ?int $isarchived;

    public function __construct(
        ?int $id,
        ?string $name,
        ?string $description,
        ?string $date,
        ?string $time,
        ?float $cost,
        ?int $vendoruser_id,
        ?int $isarchived,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->time = $time;
        $this->cost = $cost;
        $this->vendoruser_id = $vendoruser_id;
        $this->isarchived = $isarchived;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of time
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */ 
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the value of cost
     */ 
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     *
     * @return  self
     */ 
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get the value of vendoruser_id
     */ 
    public function getVendoruser_id()
    {
        return $this->vendoruser_id;
    }

    /**
     * Set the value of vendoruser_id
     *
     * @return  self
     */ 
    public function setVendoruser_id($vendoruser_id)
    {
        $this->vendoruser_id = $vendoruser_id;

        return $this;
    }

    /**
     * Get the value of isarchived
     */ 
    public function getIsarchived()
    {
        return $this->isarchived;
    }

    /**
     * Set the value of isarchived
     *
     * @return  self
     */ 
    public function setIsarchived($isarchived)
    {
        $this->isarchived = $isarchived;

        return $this;
    }
}