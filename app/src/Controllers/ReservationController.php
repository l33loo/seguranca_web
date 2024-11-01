<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
use App\Booking\Creditcard;
use App\Booking\Reservation;
use App\Template\FrontendRenderer;
use Exception;

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
        $creditCards = Creditcard::search($filters);

        $data['creditCards'] = $creditCards;

        $html = $this->renderer->render('reservations/new', $data);
        $this->response->setContent($html);
    }

    public function reserve($params)
    {
        $paymentOption = $this->request->getParameter('cc');
        // TODO: use id of user that's logged in
        $userId = 1;
        $activityId = intval($params['id']);
        if ($paymentOption === 'cc-other') {
            // TODO: validate fields of new cc
            // TODO: get other form fields
            // TODO: create new credit card and save
        } else if ($paymentOption !== null) {
            // In this case, payment option is cc id

            // TODO: set up try / catch
            // try {
                $filters = [
                    [
                        'column' => 'id',
                        'operator' => '=',
                        'value' => $paymentOption,
                    ],
                    [
                        'column' => 'user_id',
                        'operator' => '=',
                        'value' => $userId,
                    ],
                ];

                $cc = Creditcard::search($filters);
                if (count($cc) !== 1) {
                    throw new Exception('Error retrieving credit card information');
                }

                $newReservation = new Reservation($userId, $activityId, $cc[0]->getId());
                $newReservation->save();
                header('Location: /reservations/' . $newReservation->getId());
                $resParams = [
                    'id' => $newReservation->getId(),
                ];
                $this->show($resParams);
            // } catch (\Exception) {
                // TODO: display error message to user
            // }
        } else {
            // TODO: display same page with error message
        }
    }

    public function updateStatus($newStatus)
    {
        // For VENDOR
    }
}