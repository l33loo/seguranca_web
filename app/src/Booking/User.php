<?php declare(strict_types = 1);

namespace App\Booking;

use Exception;

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

    public static function getLoggedUserType(): string
    {
        if (!empty($_SESSION['logged_id']) && isset($_SESSION['isVendor'])) {
            return ($_SESSION['isVendor'] === true) ? 'vendor' : 'client';
        }

        return 'guest';
    }

    public static function getLoggedUserId(): ?int
    {
        return !empty($_SESSION['logged_id']) ? $_SESSION['logged_id'] : null;
    }

    public static function hasAccess(string $access, array $params): bool
    {
        $hasAccess = false;
        $type = self::getLoggedUserType();
        $userId = self::getLoggedUserId();
        switch($access) {
            case 'vendor':
                // Must be Vendor to access
                $hasAccess = $type === $access;

                // Vendor must 'own' activity
                if (!empty($params['activityId'])) {
                    $filters = [
                        [
                            'column' => 'vendoruser_id',
                            'operator' => '=',
                            'value' => $userId,
                        ],
                        [
                            'column' => 'id',
                            'operator' => '=',
                            'value' => $params['activityId']
                        ]
                    ];
                    $activities = Activity::search($filters);
                    $hasAccess = count($activities) === 1;
                }

                // Vendor must 'own' activity associated with reservation
                if (!empty($params['reservationId'])) {
                    try {
                        $reservation = Reservation::find($params['reservationId']);
                        $reservation->loadRelation('activity');
                        $vendorId = $reservation->getActivity()->getVendoruser_id();
                        $hasAccess = $userId === $vendorId;
                    } catch(Exception $e) {
                        $hasAccess = false;
                    }
                }

                break;
            case 'client':
                // User must be Client or Vendor to access
                $hasAccess = ($type === $access || $type === 'vendor');

                // Must be user's own reservation
                if (!empty($params['reservationId'])) {
                    $filters = [
                        [
                            'column' => 'id',
                            'operator' => '=',
                            'value' => $params['reservationId'],
                        ],
                        [
                            'column' => 'reservedbyuser_id',
                            'operator' => '=',
                            'value' => $userId,
                        ],
                    ];
                    $reservations = Reservation::search($filters);
                    $hasAccess = count($reservations) === 1;
                }

                break;
            case 'guest':
            default:
                $hasAccess = true;
                break;
        }

        return $hasAccess;
    }
}