<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\FrontendRenderer;

class UserController
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

    public function reservations() {
        $filters = [
            [
                'column' => 'reservedbyuser_id',
                'operator' => '=',
                'value' => 1,
            ],
        ];

        $reservations = \App\Booking\Reservation::search($filters, '', 'reservedon', 'DESC');
        foreach ($reservations as $reservation) {
            $reservation
                ->loadRelation('activity')
                ->loadRelation('reservationstatus', 'reservation_status')
                ->loadRelation('creditcard', 'creditcard');
        }

        $data = [
            'reservations' => $reservations,
        ];
        // TODO: change to having a user, and loading reservations onto it
        $html = $this->renderer->render('users/reservations', $data);
        $this->response->setContent($html);
    }

    public function showProfile()
    {
        $html = $this->renderer->render('users/profile');
        $this->response->setContent($html);
    }

    public function showLoginForm()
    {
        $html = $this->renderer->render('users/login');
        $this->response->setContent($html);
    }

    public function login()
    {

    }

    public function logout()
    {
        
    }
}