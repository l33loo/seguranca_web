<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\Renderer;

class Login
{
    private Request $request;
    private Response $response;
    private Renderer $renderer;

    public function __construct(
        Request $request,
        Response $response,
        Renderer $renderer
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function show()
    {
        $html = $this->renderer->render('Login');
        $this->response->setContent($html);
    }

    public function login()
    {

    }

    public function logout()
    {
        
    }
}