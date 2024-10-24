<?php declare(strict_types = 1);

namespace Booking;

class MyConnect
{
    protected $connection;
    private static $instance = null;

    private function __construct()
    {
        $config = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/mysql/mysql.dev.env');
    }
}