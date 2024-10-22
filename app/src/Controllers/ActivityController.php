<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\FrontendRenderer;

class ActivityController
{
    private Request $request;
    private Response $response;
    private FrontendRenderer $renderer;

    public function __construct(
        Request $request,
        Response $response,
        FrontendRenderer $renderer
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

    public function archive()
    {
        // If VENDOR
    }

    public function listReservations($params): void
    {
        // If VENDOR
        $data = [
            'activityId' => $params['id'],
        ];

        $html = $this->renderer->render('reservations/list', $data);
        $this->response->setContent($html);
    }
}