<?php declare(strict_types = 1);

namespace App\Booking;

class User 
{
    use DB\DBModel;

    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $passwordhash;
    protected bool $isvendor;

    public function __construct(
        ?string $firstname = null,
        ?string $lastname = null,
        ?string $email = null,
        ?string $passwordhash = null,
        ?bool $isvendor = null,
        ?int $id = null
    ){
        $this->tableName = 'user';

        if ($id !== null) {
            $this->id = $id;
        }
        if ($firstname !== null) {
            $this->firstname = $firstname;
        }
        if ($lastname !== null) {
            $this->lastname = $lastname;
        }
        if ($email !== null) {
            $this->email = $email;
        }
        if ($passwordhash !== null) {
            $this->passwordhash = $passwordhash;
        }
        if ($isvendor !== null) {
            $this->isvendor = $isvendor;
        }
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * Get the value of passwordhash
     */ 
    public function getPasswordhash(): string
    {
        return $this->passwordhash;
    }

    /**
     * Set the value of passwordhash
     *
     * @return  self
     */ 
    public function setPasswordhash(string $passwordhash): self
    {
        $this->passwordhash = $passwordhash;

        return $this;
    }

    /**
     * Get the value of isvendor
     */ 
    public function getIsvendor()
    {
        return $this->isvendor;
    }

    /**
     * Set the value of isvendor
     *
     * @return  self
     */ 
    public function setIsvendor($isvendor)
    {
        $this->isvendor = $isvendor;

        return $this;
    }
}