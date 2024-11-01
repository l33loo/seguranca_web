<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
use App\Booking\Reservation;
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

    public function new($params)
    {
        // TODO: redirect to login form if user is not logged in

        $activityId = $params['id'];

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

    public function reserve($params)
    {
        if ($this->request->getParameter('cc-other') !== null) {
            // TODO: validate fields of new cc
            // TODO: get other form fields
            // TODO: create new credit card and save
        }
        // TODO: Logged in user only
        $activityId = $params['id'];
        // $reservation = new Reservation(
        //     1,
        //     $activityId,
        //     ?int $creditcard_id = null
        // );

        // $reservation->save();

        // $params['id'] = $reservation->getId();

        $this->show($params);
    }

    public function updateStatus($newStatus)
    {
        // For VENDOR
    }
}