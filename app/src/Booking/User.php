<?php declare(strict_types = 1);

namespace App\Booking;

abstract class User 
{
    use DB\DBModel;

    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected int $creditcard_id;
    protected Creditcard $creditcard;

    public function __construct(
        ?int $id = null,
        ?string $firstname = null,
        ?string $lastname = null,
        ?string $email = null,
        ?string $password = null,
        ?int $creditcard_id = null,
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
        if ($password !== null) {
            $this->password = $password;
            }
        if ($creditcard_id !== null) {
            $this->creditcard_id = $creditcard_id;
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
     * Undocumented function
     * Usado o search()
     * @param string $password
     * @return boolean
     */
    public function validPassword(string $password): bool
    {
        if (count($this->password) == 1) {
            return password_verify($password, $this->password[0]);
        }
        return false;

        $users = self::search(['email' => trim($this->email)]);
        if ($users[0]) {
            $passwordHash = $users[0]['password'];
            return password_verify($password, $passwordHash);
        } else {
            return false;
        }

    }

    /**
     * Get the value of creditcard_id
     */ 
    public function getCreditcard_id()
    {
        return $this->creditcard_id;
    }

    /**
     * Set the value of creditcard_id
     *
     * @return  self
     */ 
    public function setCreditcard_id($creditcard_id)
    {
        $this->creditcard_id = $creditcard_id;

        return $this;
    }
}