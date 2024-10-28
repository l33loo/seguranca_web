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

}