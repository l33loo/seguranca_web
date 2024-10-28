<?php declare(strict_types = 1);

namespace App\Booking;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

class Comment
{
    use DB\DBModel;

    protected string $comment;
    protected int $user_id;
    protected string $postedon;
    protected int $activity_id;

    public function __construct(
        ?int $id = null,
        ?string $comment = null,
        ?int $user_id = null,
        ?string $postedon = null,
        ?int $activity_id = null
    ) {
        if ($id !== null) {
            $this->id = $id;
        }
        if ($comment !== null) {
            $this->comment = $comment;
        }
        if ($user_id !== null) {
            $this->user_id = $user_id;
        }
        if ($postedon !== null) {
            $this->postedon = $postedon;
        }
        if ($activity_id !== null) {
            $this->activity_id = $activity_id;
        }
    }

    /**
     * Get the value of comment
     */ 
    public function getComment(): string
    {
            return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment(string $comment): self
    {
            $this->comment = $comment;

            return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): int
    {
            return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id(int $user_id): self
    {
            $this->user_id = $user_id;

            return $this;
    }

    /**
     * Get the value of postedon
     */ 
    public function getPostedon(): string
    {
            return $this->postedon;
    }

    /**
     * Set the value of postedon
     *
     * @return  self
     */ 
    public function setPostedon(string $postedon): self
    {
            $this->postedon = $postedon;

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

    public function getPostedOnToString(): string
    {
        return \App\Utils\Helper::dateTimeToString($this->postedon);
    }
}