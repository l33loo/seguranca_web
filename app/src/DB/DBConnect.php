<?php declare(strict_types = 1);

namespace App\DB;

interface DBConnect
{
    public static function getInstance(): self;
    public function escapedInsertQuery(string $sql, array $params): string|false;
    public function escapedUpdateQuery(string $sql, array $params): void;
    public function escapedSelectQuery(string $sql, array $params): \PDOStatement|false;
}