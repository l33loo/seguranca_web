<?php declare(strict_types = 1);

namespace App\Menu;

class ArrayMenuReader implements MenuReader
{
    // TODO: implement conditional logic for 
    // guests, clients, and vendors
    public function readMenu(): array
    {
        return [
            ['href' => '/', 'text' => 'Homepage'],
        ];
    }
}