<?php declare(strict_types = 1);

namespace App\Booking;

class Creditcard
{
    use DB\DBModel;
    use Validation\FormValidatorTrait;

    protected string $number;
    protected ?string $cvv;
    protected ?string $expiry;
    protected ?int $user_id;

    public function __construct(
        ?string $number = null,
        ?string $cvv = null,
        ?string $expiry = null,
        ?int $user_id = null,
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
        if ($user_id !== null) {
            $this->user_id = $user_id;
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
        // return self::obfuscateNum($this->number);
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
    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    /**
     * Set the value of cvv
     *
     * @return  self
     */ 
    public function setCvv(?string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Get the value of expiry
     */ 
    public function getExpiry(): ?string
    {
        return $this->expiry;
    }

    /**
     * Set the value of expiry
     *
     * @return  self
     */ 
    public function setExpiry(?string $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }
    

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): ?int
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public static function obfuscateNum(string $num)
    {
        return str_repeat('*', strlen($num) - 4) . substr($num, -4);
    }

    public static function getValidationRules(): array
    {
        return [
            'number' => [
                'name' => 'number',
                'required' => true,
                'regexMatch' => '/^\d{15,16}$/',
            ],
            'cvv' => [
                'name' => 'cvv',
                'required' => true,
                'regexMatch' => '/^\d{3,4}$/',
            ],
            'expiry' => [
                'name' => 'expiry',
                'required' => true,
                'type' => 'dateString',
                'mustBeInFuture' => true,
            ],
        ];
    }
}