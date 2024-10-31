<?php declare(strict_types = 1);

namespace App\Booking;

class ReservationStatus
{
    use DB\DBModel;

    protected string $name;

    public function __construct(?string $name, ?int $id)
    {
        if ($name !== null) {
            $this->name = $name;
        }
        if ($id !== null) {
            $this->id = $id;
        }
    }
}