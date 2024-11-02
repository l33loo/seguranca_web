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
                'column' => "CONCAT(date, ' ', time)",
                'operator' => '>',
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'column' => 'isArchived',
                'operator' => '=',
                'value' => 'false',
            ]
        ];

        $search = $this->request->getQueryParameter('search');
        if (!empty($search) && strlen(trim($search)) > 0) {
            $filters[] = [
                'column' => 'name',
                'operator' => 'LIKE',
                'value' => '%' . trim($search) . '%',

            ];

            $data['search'] = htmlspecialchars($search);
        }

        $data['activities'] = Activity::search($filters, '', 'date');

        $html = $this->renderer->render('activities/list', $data);
        $this->response->setContent($html);
    }

    public function show($params)
    {
        $data = [
            'activity' => Activity::find(intval($params['activityId']))->loadComments()/* TODO: ->loadRelation('user') */,
        ];
        
        $html = $this->renderer->render('activities/show', $data);
        $this->response->setContent($html);
    }

    public function new()
    {
        // If VENDOR
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
            'activityId' => $params['activityId'],
        ];

        $html = $this->renderer->render('reservations/list', $data);
        $this->response->setContent($html);
    }

    public function postComment($params): void
    {
        $newComment = new \App\Booking\Comment(
            htmlspecialchars($this->request->getBodyParameters()['comment']),
            // TODO: put id of logged-in user
            2,
            intval($params['activityId'])
        );

        $newComment->save();
        $this->show($params);
    }
}