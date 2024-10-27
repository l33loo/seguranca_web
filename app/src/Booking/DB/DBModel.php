<?php declare(strict_types = 1);

namespace App\Booking\DB;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use App\Booking\DB\MyConnect;

trait DBModel
{
    protected $tableName = '';
    protected ?int $id = null;

    /**
     * Get the value of id
     *   
     * @return int
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     * 
     * @return self
     */ 
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;

    }

    public function save()
    {
        $connection = MyConnect::getInstance();

        $properties = get_object_vars($this);
        unset($properties['tableName']);
        unset($properties['id']);

        // Strip properties that are objects or arrays, because the latter
        // are stored in different tables.
        foreach ($properties as $propertyKey => $propertyValue) {
            // If there is a corresponding `{property}_id`, it means the current
            // property is for an object that maps to another db table.
            if (key_exists($propertyKey . '_id', $properties) || is_array($propertyValue)) {
                unset($properties[$propertyKey]);
            }
        }

        if (empty($this->id)) {
            $sql = 'INSERT INTO ' . $this->tableName . ' (' . implode(',', array_keys($properties)).') VALUES(';
            $params = [];
            foreach ($properties as $property => $value) {
                $sql .= '?,';

                // PDO does not accept booleans, so they need to be converted
                // to their int equivalent.
                $params[] = is_bool($value) ? (int)$value : $value;
            }

            $sql = rtrim($sql, ',');
            $sql .= ');';

            $this->id = $connection->escapedInsertQuery($sql, $params);
        } else {
            $sql = 'UPDATE ' . $this->tableName . ' SET ';
            $params = [];
            foreach ($properties as $property => $value) {
                $sql .= $property . ' = ?,';

                // PDO does not accept booleans, so they need to be converted
                // to their int equivalent.
                $params[] = is_bool($value) ? (int)$value: $value;
            }

            $sql = rtrim($sql, ',');
            $sql .= ' WHERE id = ?;';
            $params[] = $this->id;

            $connection->escapedUpdateQuery($sql, $params);
        }
    }

    public static function find(int $id, string $tableName = ''): self
    {
        $connection = MyConnect::getInstance();

        if ($tableName === '') {
            $class_parts = explode('\\', static::class);
            $tableName = end($class_parts);
            $tableName = strtolower($tableName);
        }

        $sql = "SELECT * FROM " . $tableName . " WHERE id = ?";
        $stmt = $connection->escapedSelectQuery($sql, [$id]);
        $result = $stmt->fetchObject(static::class);

        if (!$result) {
            throw new \Exception('Error retrieving row. Number of results not equal to 1.');
        }

        return $result;
    }

    public static function search(array $filters = [], string $tableName = '', ?string $orderBy = null, ?string $order = null, ?int $limit = null): array
    {
        if ($tableName === '') {
            $class_parts = explode('\\', static::class);
            $tableName = end($class_parts);
            $tableName = strtolower($tableName);
        }

        $sql = "SELECT * FROM " . $tableName;

        if (!empty($filters) && count($filters) > 0) {
            $sql .= " WHERE ";

            foreach ($filters as $pos => $filter) {
                if ($pos !== 0) {
                    $sql .= ' AND ';
                }

                $sql .= $filter['column'] . ' ' . $filter['operator'] . ' ?';
            }
        }

        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        if (!empty($order)) {
            $sql .= " $order";
        }

        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }

        $params = array_column($filters, 'value');

        $connection = MyConnect::getInstance();
        $stmt = $connection->escapedSelectQuery($sql, $params);

        $results = $stmt->fetchAll(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, static::class);

        return $results;
    }

    public function loadRelation(string $relationName, string $tableName = ''): self
    {
        if ($this->{$relationName . '_id'} === null) {
            return $this;
        }
        
        if ($tableName === '') {
            $tableName = $relationName;
        }
        
        $className = 'App\\Booking\\' . self::snakeToCamel($tableName);
        
        $this->{$relationName} = $className::find($this->{$relationName . '_id'}, $tableName);

        return $this;
    }

    public static function snakeToCamel($string, $capitalizeFirstCharacter = true): string
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
    
        return $str;
    }
}