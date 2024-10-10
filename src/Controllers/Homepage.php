<?php declare(strict_types = 1);

namespace App\Controllers;

use \Http\Response;

class Homepage
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function show()
    {
        $this->response->setContent('Hello World!');
    }
}