<?php declare(strict_types = 1);

namespace App\Booking;

class Reservation
{
    use DB\DBModel;

    protected int $reservedbyuser_id;
    protected string $reservedon;
    protected int $activity_id;
    protected int $creditcard_id;
    protected int $reservationstatus_id;

    public function __construct(
        ?int $reservedbyuser_id,
        ?string $reservedon,
        ?int $activity_id,
        ?int $creditcard_id,
        ?int $reservationstatus_id,
        ?int $id
    ) {
        if ($reservedbyuser_id !== null) {
            $this->reservedbyuser_id = $reservedbyuser_id;
        }
        if ($reservedon !== null) {
            $this->reservedon = $reservedon;
        }
        if ($activity_id !== null) {
            $this->activity_id = $activity_id;
        }
        if ($creditcard_id !== null) {
            $this->creditcard_id = $creditcard_id;
        }
        if ($reservationstatus_id !== null) {
            $this->reservationstatus_id = $reservationstatus_id;
        }
        if ($id !== null) {
            $this->id = $id;
        }
    }

    /**
     * Get the value of reservedbyuser_id
     */ 
    public function getReservedbyuser_id(): int
    {
        return $this->reservedbyuser_id;
    }

    /**
     * Set the value of reservedbyuser_id
     *
     * @return  self
     */ 
    public function setReservedbyuser_id(int $reservedbyuser_id): self
    {
        $this->reservedbyuser_id = $reservedbyuser_id;

        return $this;
    }

    /**
     * Get the value of reservedon
     */ 
    public function getReservedon(): string
    {
        return $this->reservedon;
    }

    /**
     * Set the value of reservedon
     *
     * @return  self
     */ 
    public function setReservedon(string $reservedon): self
    {
        $this->reservedon = $reservedon;

        return $this;
    }

    /**
     * Get the value of activity_id
     */ 
    public function getActivity_id(): int
    {
        return $this->activity_id;
    }

    /**
     * Set the value of activity_id
     *
     * @return  self
     */ 
    public function setActivity_id(int $activity_id): self
    {
        $this->activity_id = $activity_id;

        return $this;
    }

    /**
     * Get the value of creditcard_id
     */ 
    public function getCreditcard_id(): int
    {
        return $this->creditcard_id;
    }

    /**
     * Set the value of creditcard_id
     *
     * @return  self
     */ 
    public function setCreditcard_id(int $creditcard_id): self
    {
        $this->creditcard_id = $creditcard_id;

        return $this;
    }

    /**
     * Get the value of reservationstatus_id
     */ 
    public function getReservationstatus_id(): int
    {
        return $this->reservationstatus_id;
    }

    /**
     * Set the value of reservationstatus_id
     *
     * @return  self
     */ 
    public function setReservationstatus_id(int $reservationstatus_id): self
    {
        $this->reservationstatus_id = $reservationstatus_id;

        return $this;
    }
}