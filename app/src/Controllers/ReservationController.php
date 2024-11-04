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
            ->loadRelation('reservedbyuser', 'user')
            ->loadRelation('reservationstatus', 'reservation_status');
        $reservation->getCreditcard()->decryptAll();
        $reservation->getCreditcard()->obfuscateNum();

        $data = [
            'reservation' => $reservation,
        ];

        $html = $this->renderer->render('reservations/show', $data);
        $this->response->setContent($html);
    }

    public function new($params)
    {
        $activityId = $params['activityId'];
        $activity = Activity::find(intval($activityId));
        if ($activity->hasPassed() || $activity->getIsarchived()) {
            // TODO: display error
            header('Location: /');
            $activityController = new ActivityController($this->request, $this->response, $this->renderer);
            $activityController->list();
            return;
        }

        $data = [
            'activity' => $activity,
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
        foreach ($creditCards as $cc) {
            $cc->decryptAll();
            $cc->obfuscateNum();
        }

        $data['creditCards'] = $creditCards;

        $html = $this->renderer->render('reservations/new', $data);
        $this->response->setContent($html);
    }

    public function reserve($params)
    {
        $activityId = intval($params['activityId']);
        $activity = Activity::find(intval($activityId));
        $data = [];
        if ($activity->hasPassed() || $activity->getIsarchived()) {
            $data['error'] = 'Activity "' . $activity->getName() . ' cannot be reserved.';
            header('Location: /');
            $html = $this->renderer->render('activities/list', $data);
            $this->response->setContent($html);
            return;
        }

        $paymentOption = $this->request->getParameter('cc');

        // No credit card selected
        if ($paymentOption === null) {
            $data['error'] = 'You must pick a credit card.';
            $html = $this->renderer->render('reservations/new', $data);
            $this->response->setContent($html);
            return;
        }

        $userId = User::getLoggedUserId();;
        // New credit card
        if ($paymentOption === 'cc-other') {
            $ccNumber = $this->request->getParameter('number');
            $ccExpiry = $this->request->getParameter('expiry');
            $cvv = $this->request->getParameter('cvv');
            $ccData = new Creditcard($ccNumber, $cvv, $ccExpiry);

            $validOrErrors = $ccData->validateForm($this->request->getParameters());
            if ($validOrErrors !== true) {
                $data['errors'] = $validOrErrors;
                $html = $this->renderer->render('reservations/new', $data);
                $this->response->setContent($html);
                return;
            }

            // New credit card is valid.
            // User requested to save credit card to their account
            $requestToSave = $this->request->getParameter('cc-save');
            if ($requestToSave === 'yes') {
                $ccData
                    ->setUser_id(User::getLoggedUserId())
                    ->encryptAndSave();
            
            } else {
                // User doesn't want to save their cc to their account,
                // so hide all numbers except last 4 for record keeping
                $ccData
                    ->obfuscateNum()
                    ->setCvv(null)
                    ->encryptAndSave();
            }
            
            $newRes = new Reservation($userId, $activityId, $ccData->getId());
            $newRes->save();
            $data['success'] = 'Reservation made with success.';
            $data['reservation'] = Reservation::find($newRes->getId())
                ->loadRelation('activity')
                ->loadRelation('creditcard')
                ->loadRelation('reservedbyuser', 'user')
                ->loadRelation('reservationstatus', 'reservation_status');
            $data['reservation']->getCreditcard()->decryptAll();
            $data['reservation']->getCreditcard()->obfuscateNum();
            $data['success'] = 'Reservation made with success.';
            header('Location: /reservations/' . $newRes->getId());
            $html = $this->renderer->render('reservations/show', $data);
            $this->response->setContent($html);
            return;
        }
        
        // In this case, the user chose an existing credit card.
        // Payment option is cc id
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
            $data['error'] = 'Error retrieving credit card information.';
            $html = $this->renderer->render('reservations/new', $data);
            $this->response->setContent($html);
            return;
        }

        // Existing credit card successfully retrieved
        $newReservation = new Reservation($userId, $activityId, $cc[0]->getId());
        $newReservation->save();
        $data['success'] = 'Reservation made with success.';
        $data['reservation'] = Reservation::find($newReservation->getId())
            ->loadRelation('activity')
            ->loadRelation('creditcard')
            ->loadRelation('reservedbyuser', 'user')
            ->loadRelation('reservationstatus', 'reservation_status');
        $data['reservation']->getCreditcard()->decryptAll();
        $data['reservation']->getCreditcard()->obfuscateNum();
        header('Location: /reservations/' . $newReservation->getId());
        $html = $this->renderer->render('reservations/show', $data);
        $this->response->setContent($html);
    }

    public function editStatus($params)
    {
        $newStatusId = $this->request->getParameter('status');
        $reservation = Reservation::find(intval($params['reservationId']));
        $reservation->loadRelation('activity');
        $activity = $reservation->getActivity();
        $activityId = $activity->getId();

        $reservationStatuses = \App\Booking\ReservationStatus::search([], 'reservation_status', 'id'); 
        $activity->loadReservations();

        if (empty($newStatusId) || $activity->hasPassed() || $activity->getIsarchived()) {
            // TODO: error
        } else {
            $reservation->setReservationstatus_id(intval($newStatusId))->save();
        }

        $data = [
            'activity' => $activity,
            'reservationStatuses' => $reservationStatuses,
        ];
        header("Location: /users/me/activities/$activityId");
        $html = $this->renderer->render('users/vendors/activities/show', $data);
        $this->response->setContent($html);
    }
}