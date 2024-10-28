<?php declare(strict_types = 1);

namespace App\Booking;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

abstract class User 
{
    use DB\DBModel;

    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected array $creditcards;

    public function __construct(
        ?int $id = null,
        ?string $firstname = null,
        ?string $lastname = null,
        ?string $email = null,
        ?string $password = null,
        ?array $creditcards = null
    ){
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
        if ($password !== null) {
            $this->password = $password;
            }
        if ($creditcards !== null) {
            $this->creditcards = $creditcards;
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
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of creditcards
     */ 
    public function getCreditcards(): int
    {
        return $this->creditcards;
    }

    /**
     * Set the value of creditcards
     *
     * @return  self
     */ 
    public function setCreditcards(int $creditcards): self
    {
        $this->creditcards = $creditcards;

        return $this;
    }
}