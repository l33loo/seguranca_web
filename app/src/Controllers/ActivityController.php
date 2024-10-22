<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\Renderer;

class ActivityController
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

    public function list()
    {
        $html = $this->renderer->render('activities/list');
        $this->response->setContent($html);
    }

    public function show($params)
    {
        $data = [
            'id' => $params['id'],
        ];
        
        $html = $this->renderer->render('activities/show', $data);
        $this->response->setContent($html);
    }

    public function create()
    {
        // If VENDOR
    }

    public function update()
    {
        // IF VENDOR
    }
}