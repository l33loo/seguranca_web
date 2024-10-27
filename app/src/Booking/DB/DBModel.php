<?php declare(strict_types = 1);

namespace App\DB;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/vendor/autoload.php';

use App\DB\MyConnect;

trait DBModel
{
    protected $tableName = '';
    protected ?int $id = null;

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function save()
    {
        $connection = MyConnect::getInstance();

        $properties = get_class_vars(get_class($this));
        unset($properties['tableName']);
        unset($properties['id']);
    }
}