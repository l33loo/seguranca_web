<?php declare(strict_types = 1);

namespace App\Booking;

class ReservationStatus
{
    use DB\DBModel;

    protected string $name;

    public function __construct(?string $name = null, ?int $id = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
        if ($id !== null) {
            $this->id = $id;
        }
    }
    

    /**
     * Get the value of name
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}