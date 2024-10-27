<?php declare(strict_types = 1);

namespace App\Booking;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

class Activity
{
    use DB\DBModel;

    protected string $name;
    protected string $description;
    protected string $date;
    protected string $time;
    protected float $cost;
    protected int $vendoruser_id;
    protected array $vendor;
    protected int $isarchived;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?string $description = null,
        ?string $date = null,
        ?string $time = null,
        ?float $cost = null,
        ?int $vendoruser_id = null,
        ?int $isarchived = null,
    ) {
        if ($id !== null) {
            $this->id = $id;
        }
        if ($name !== null) {
            $this->name = $name;
        }
        if ($description !== null) {
            $this->description = $description;
        }
        if ($date !== null) {
            $this->date = $date;
        }
        if ($time !== null) {
            $this->time = $time;
        }
        if ($cost !== null) {
            $this->cost = $cost;
        }
        if ($vendoruser_id !== null) {
            $this->vendoruser_id = $vendoruser_id;
        }
        if ($isarchived !== null) {
            $this->isarchived = $isarchived;
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

    /**
     * Get the value of description
     */ 
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of time
     */ 
    public function getTime():string
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */ 
    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the value of cost
     */ 
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * Set the value of cost
     *
     * @return  self
     */ 
    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get the value of vendoruser_id
     */ 
    public function getVendoruser_id(): int
    {
        return $this->vendoruser_id;
    }

    /**
     * Set the value of vendoruser_id
     *
     * @return  self
     */ 
    public function setVendoruser_id(int $vendoruser_id): self
    {
        $this->vendoruser_id = $vendoruser_id;

        return $this;
    }

    /**
     * Get the value of isarchived
     */ 
    public function getIsarchived(): int
    {
        return $this->isarchived;
    }

    /**
     * Set the value of isarchived
     *
     * @return  self
     */ 
    public function setIsarchived(int $isarchived): self
    {
        $this->isarchived = $isarchived;

        return $this;
    }
}