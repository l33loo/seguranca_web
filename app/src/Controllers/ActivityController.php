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
            'activity' => Activity::find(intval($params['activityId']))
                ->loadComments()
                ->loadRelation('vendoruser', 'user'),
        ];
        
        $html = $this->renderer->render('activities/show', $data);
        $this->response->setContent($html);
    }

    public function new()
    {
        // TODO
    }

    public function create()
    {
        // TODO
    }

    public function editForm($params)
    {
        // TODO: add errors
        $activity = Activity::find(intval($params['activityId']));
        $data = [
            'activity' => $activity,
        ];

        $html = $this->renderer->render('activities/edit', $data);
        $this->response->setContent($html);
    }

    public function edit($params)
    {
        $name = $this->request->getParameter('name');
        $description = $this->request->getParameter('description');
        $date = $this->request->getParameter('date');
        $time = $this->request->getParameter('time');
        $cost = $this->request->getParameter('cost');

        $activity = Activity::find(intval($params['activityId']));
        
        $activity
            ->setName($name)
            ->setDescription($description)
            ->setDate($date)
            ->setTime($time)
            ->setCost(floatval($cost));
        // TODO: $activity->validate();
     
        $activity->save();
        $data = [
            'activity' => $activity,
        ];

        $html = $this->renderer->render('activities/edit', $data);
        $this->response->setContent($html);
    }

    public function archive()
    {
        // TODO
    }

    public function delete()
    {
        // TODO
    }

    public function listReservations($params): void
    {
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