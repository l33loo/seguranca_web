<?php declare(strict_types = 1);

namespace App\Booking;

class Creditcard
{
    use DB\DBModel;

    protected string $number;
    protected string $cvv;
    protected string $expiry;

    public function __construct(
        ?string $number = null,
        ?string $cvv = null,
        ?string $expiry = null,
        ?int $id = null
    ) {
        $this->tableName = 'creditcard';

        if ($number !== null) {
            $this->number = $number;
        }
        if ($cvv !== null) {
            $this->cvv = $cvv;
        }
        if ($expiry !== null) {
            $this->expiry = $expiry;
        }
        if ($id !== null) {
            $this->id = $id;
        }
    }
    

    /**
     * Get the value of number
     */ 
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Set the value of number
     *
     * @return  self
     */ 
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get the value of cvv
     */ 
    public function getCvv(): string
    {
        return $this->cvv;
    }

    /**
     * Set the value of cvv
     *
     * @return  self
     */ 
    public function setCvv(string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Get the value of expiry
     */ 
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    /**
     * Set the value of expiry
     *
     * @return  self
     */ 
    public function setExpiry(string $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }
}