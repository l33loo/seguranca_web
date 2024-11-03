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

    public function obfuscateNum(): self
    {
        $num = $this->number;
        $this->number = str_repeat('*', strlen($num) - 4) . substr($num, -4);
        return $this;
    }

    private static function encrypt($data): string
    {
        $key = getenv('ENCRYPTION_KEY');
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedCard = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        // Store IV along with the encrypted data for decryption later
        return base64_encode($encryptedCard . '::' . $iv);
    }

    public function encryptAndSave(): void
    {
        $this->number = self::encrypt($this->number);
        $this->expiry = self::encrypt($this->expiry);
        if ($this->cvv !== null) {
            $this->cvv = self::encrypt($this->cvv);
        }
        $this->save();
    }

    private static function decrypt($data): string
    {
        $key = getenv('ENCRYPTION_KEY');
        list($encryptedCard, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedCard, 'aes-256-cbc', $key, 0, $iv);
    }

    public function decryptAll(): void
    {
        $this->number = self::decrypt($this->number);
        $this->expiry = self::decrypt($this->expiry);
        if ($this->cvv !== null) {
            $this->cvv = self::decrypt($this->cvv);
        }
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