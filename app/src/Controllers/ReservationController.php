<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
use App\Booking\Creditcard;
use App\Booking\Reservation;
use App\Booking\User;
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
        $reservation = Reservation::find(intval($params['reservationId']));
        $reservation
            ->loadRelation('activity')
            ->loadRelation('creditcard')
            ->loadRelation('reservationstatus', 'reservation_status');

        $data = [
            'reservation' => $reservation,
        ];

        $html = $this->renderer->render('reservations/show', $data);
        $this->response->setContent($html);
    }

    public function new($params)
    {
        $activityId = $params['activityId'];

        $data = [
            'activity' => Activity::find(intval($activityId)),
        ];

        $userId = User::getLoggedUserId();
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

        $creditCards = Creditcard::search($filters);

        $data['creditCards'] = $creditCards;

        $html = $this->renderer->render('reservations/new', $data);
        $this->response->setContent($html);
    }

    public function reserve($params)
    {
        $paymentOption = $this->request->getParameter('cc');

        // No credit card selected
        if ($paymentOption === null) {
            // TODO: add errors
            $data = [

            ];
            $html = $this->renderer->render('reservations/new', $data);
            $this->response->setContent($html);
            return;
        }

        $userId = User::getLoggedUserId();;
        $activityId = intval($params['reservationId']);
        // New credit card
        if ($paymentOption === 'cc-other') {
            $ccNumber = $this->request->getParameter('ccNumber');
            $ccExpiry = $this->request->getParameter('ccExpiry');
            $cvv = $this->request->getParameter('cvv');
            $ccData = new Creditcard(trim($ccNumber), trim($cvv), trim($ccExpiry));
            // TODO: implement validation
            // $validation = $ccData->validate();
            // TODO: something like if $validation->hasErrors()
            $isValid = true;
            if (!$isValid) {
                // TODO: display error messages
                $data = [

                ];
                $html = $this->renderer->render('reservations/new', $data);
                $this->response->setContent($html);
                return;
            }

            // User requested to save credit card to their account
            $requestToSave = $this->request->getParameter('cc-save');
            if ($requestToSave === 'yes') {
                $ccData->setUser_id(User::getLoggedUserId())->save();
            
            } else {
                // User doesn't want to save their cc to their account,
                // so hide all numbers except last 4 for record keeping
                $obfNumber = Creditcard::obfuscateNum($ccData->getNumber(), -4);
                $ccData->setNumber($obfNumber)->setExpiry(null)->setCvv(null)->save();
            }
            
            $newRes = new Reservation($userId, $activityId, $ccData->getId());
            $newRes->save();
            header('Location: /reservations/' . $newRes->getId());
            $data = [
                'id' => $newRes->getId(),
            ];
            $html = $this->renderer->render('reservations/show', $data);
            $this->response->setContent($html);
            return;
        }
        
        // In this case, the user chose an existing credit card.
        // Payment option is cc id

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
                [
                    'column' => "expiry",
                    'operator' => '>',
                    'value' => date('Y-m-d'),
                ],
            ];

            $cc = Creditcard::search($filters);
            if (count($cc) !== 1) {
                throw new Exception('Error retrieving credit card information');
            }

            $newReservation = new Reservation($userId, $activityId, $cc[0]->getId());
            $newReservation->save();
            header('Location: /reservations/' . $newReservation->getId());
            $data = [
                'id' => $newReservation->getId(),
            ];
            $html = $this->renderer->render('reservations/show', $data);
            $this->response->setContent($html);
        // } catch...
    }

    public function editStatus($params)
    {
        $newStatusId = $this->request->getParameter('status');
        if (!empty($newStatusId)) {
            $reservation = Reservation::find(intval($params['reservationId']));
            $reservation->setReservationstatus_id(intval($newStatusId))->save();
        }

        $activityId = $reservation->getActivity_id();
        $activity = Activity::find(intval($activityId));
        $reservationStatuses = \App\Booking\ReservationStatus::search([], 'reservation_status', 'id'); 
        $activity->loadReservations();
        $data = [
            'activity' => $activity,
            'reservationStatuses' => $reservationStatuses,
        ];
        header("Location: /users/me/activities/$activityId");
        $html = $this->renderer->render('users/vendors/activities/show', $data);
        $this->response->setContent($html);
    }
}