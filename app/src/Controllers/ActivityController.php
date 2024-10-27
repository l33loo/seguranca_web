<?php declare(strict_types = 1);
namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
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
        $data = [];
        $filters = [
            [
                'column' => 'DATE(date) + 0',
                'operator' => '>=',
                'value' => 'CURDATE() + 0',
            ],
            [
                'column' => 'TIME(time) + 0',
                'operator' => '>=',
                'value' => 'CURTIME() + 0',
            ],
            [
                'column' => 'isArchived',
                'operator' => '=',
                'value' => 'false',
            ]
        ];

        // WHERE  DATE(c.create_date) = date(NOW());
        $search = $this->request->getQueryParameter('search');
        if (!empty($search) && strlen(trim($search)) > 0) {
            $filters[] = [
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%' . trim($search) . '%',

            ];

            $data['search'] = htmlspecialchars($search);
        }

        try {
            $data['activities'] = Activity::search($filters);
        } catch(\PDOException $e) {
            // TODO: fix error handling
            echo 'ERROR <3: ' . $e->getMessage();
        }

        $html = $this->renderer->render('activities/list', $data);
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