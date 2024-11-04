<?php declare(strict_types = 1);

namespace App\Booking;

use App\Booking\Comment;

class Activity
{
    use DB\DBModel;
    use Validation\FormValidatorTrait;

    protected string $name;
    protected string $description;
    protected string $date;
    protected string $time;
    protected float $cost;
    protected int $vendoruser_id;
    protected User $vendoruser;
    protected array $comments;
    protected array $reservations;
    protected bool $isarchived;

    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?string $date = null,
        ?string $time = null,
        ?float $cost = null,
        ?int $vendoruser_id = null,
        ?bool $isarchived = null,
        ?int $id = null,
    ) {
        $this->tableName = 'activity';
        
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
            // $t = strtotime($time);
            // $this->time = date('H:i',$t);
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
        return date('H:i', strtotime($this->time));
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
    public function getIsarchived(): bool
    {
        return $this->isarchived;
    }

    /**
     * Set the value of isarchived
     *
     * @return  self
     */ 
    public function setIsarchived(bool $isarchived): self
    {
        $this->isarchived = $isarchived;

        return $this;
    }

    public function getCostToString(): string
    {
        return \App\Utils\Helper::priceToString($this->cost);
    }

    public function getDateTimeToString(): string
    {
        return \App\Utils\Helper::dateTimeToString($this->date, $this->time);
    }

    /**
     * Get the value of comments
     */ 
    public function getComments(): array
    {
        return $this->comments;
    }

    public function loadComments(): self
    {
        $filter = [
            [
                'column' => 'activity_id',
                'operator' => '=',
                'value' => $this->id,
            ],
        ];

        $this->comments = Comment::search($filter, '', 'postedon', 'DESC');

        foreach ($this->comments as $comment) {
            $comment->loadRelation('user');
        }
        return $this;
    }

    /**
     * Get the value of reservations
     */ 
    public function getReservations(): array
    {
        return $this->reservations;
    }

    public function loadReservations(): self
    {
        $filter = [
            [
                'column' => 'activity_id',
                'operator' => '=',
                'value' => $this->id,
            ],
        ];

        $this->reservations = Reservation::search($filter, '', 'id', 'DESC');

        foreach ($this->reservations as $reservation) {
            $reservation
                ->loadRelation('reservedbyuser', 'user')
                ->loadRelation('creditcard')
                ->loadRelation('reservationstatus', 'reservation_status');
            $reservation->getCreditcard()->decryptAll();
            $reservation->getCreditcard()->obfuscateNum();
        }
        return $this;
    }

    public function hasPassed(): bool
    {
        $format = 'Y-m-d H:i';
        $now = date_create_from_format($format, date($format));
        $activityDate = date_create_from_format($format, $this->getDate() . ' ' . $this->getTime());
        return $now > $activityDate;
    }

    public static function getValidationRules(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'required' => true,
                'maxLength' => 45,
            ],
            'description' => [
                'name' => 'description',
                'required' => true,
                'maxLength' => 215,
            ],
            'date' => [
                'name' => 'date',
                'required' => true,
                'type' => 'dateString',
                'mustBeInFuture' => true,
            ],
            'time' => [
                'name' => 'time',
                'required' => true,
                // 'type' => 'timeString',
            ],
        ];
    }
}