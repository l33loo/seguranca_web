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

    public function vendorActivities(): void
    {
        // TODO: get logged-in user's id
        $vendorId = 1;
        $data = [];
        $filters = [
            [
                'column' => "vendoruser_id",
                'operator' => '=',
                'value' => $vendorId,
            ],
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

        $data['activities'] = \App\Booking\Activity::search($filters, '', 'date');

        $html = $this->renderer->render('/users/vendors/activities/list', $data);
        $this->response->setContent($html);
    }

    public function reservations() {
        $filters = [
            [
                'column' => 'reservedbyuser_id',
                'operator' => '=',
                'value' => 1, // TODO: change to having a user, and loading reservations onto it
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