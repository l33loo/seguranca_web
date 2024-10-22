<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\Renderer;

class ClientController
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

    public function showProfile()
    {
        $html = $this->renderer->render('users/profile');
        $this->response->setContent($html);
    }

    public function showReservations()
    {
        
    }
}