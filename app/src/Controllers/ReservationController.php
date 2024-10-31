<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
use App\Template\FrontendRenderer;

class ReservationController
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
        $html = $this->renderer->render('reservations/list');
        $this->response->setContent($html);
    }

    public function show($params)
    {
        $data = [
            'id' => $params['id'],
        ];
        
        $html = $this->renderer->render('reservations/show', $data);
        $this->response->setContent($html);
    }

    public function new()
    {
        // TODO: redirect to login form if user is not logged in

        $activityId = $this->request->getQueryParameter('activity');

        $data = [
            'activity' => Activity::find(intval($activityId)),
        ];

        // TODO: use id of logged-in user
        $userId = 1;
        $filters = [
            [
                'column' => 'user_id',
                'operator' => '=',
                'value' => $userId,
            ],
            [
                'column' => "expiry",
                'operator' => '>',
                'value' => date('Y-m-d'),
            ],
        ];

        // TODO: Expose just the last 4 digits of the credit card
        $creditCards = \App\Booking\Creditcard::search($filters);

        $data['creditCards'] = $creditCards;

        $html = $this->renderer->render('reservations/new', $data);
        $this->response->setContent($html);
    }

    public function reserve($ids)
    {
        // Logged in user only
    }

    public function updateStatus($newStatus)
    {
        // For VENDOR
    }
}