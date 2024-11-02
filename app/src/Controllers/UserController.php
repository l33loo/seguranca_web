<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Booking\Activity;
use App\Booking\ReservationStatus;
use Http\Request;
use Http\Response;
use App\Booking\User;
use App\Template\FrontendRenderer;
use Exception;

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
        $vendorId = User::getLoggedUserId();;
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

        $data['activities'] = \App\Booking\Activity::search($filters, '', 'id', 'DESC');

        $html = $this->renderer->render('/users/vendors/activities/list', $data);
        $this->response->setContent($html);
    }

    public function reservations() {
        $filters = [
            [
                'column' => 'reservedbyuser_id',
                'operator' => '=',
                'value' => User::getLoggedUserId(),
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

    public function vendorActivity($params): void
    {
        $activity = Activity::find(intval($params['activityId']));
        $reservationStatuses = ReservationStatus::search([], 'reservation_status', 'id'); 
        $activity->loadReservations();
        $data = [
            'activity' => $activity,
            'reservationStatuses' => $reservationStatuses,
        ];
        $html = $this->renderer->render('users/vendors/activities/show', $data);
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
        $email = $this->request->getParameter('email');
        $password = $this->request->getParameter('password');
        $filters = [
            [
                'column' => 'email',
                'operator' => '=',
                'value' => trim($email),
            ]
        ];
        $users = User::search($filters);
        
        // User found and valid password
        if (count($users) === 1 && password_verify(trim($password), $users[0]->getPasswordhash()) === true) {
            $user = $users[0];
            $_SESSION['logged_id'] = $user->getId();
            $_SESSION['name'] = $user->getFirstname();
            $_SESSION['isVendor'] = $user->getIsvendor();

            $redirect = $this->request->getQueryParameter('redirect');
            if (!empty($redirect)) {
                header("Location: $redirect");
                // TODO: set template based on redirect, if possible
                $html = $this->renderer->render('users/profile');
                $this->response->setContent($html);
                return;
            }

            if (User::getLoggedUserType() === 'vendor' ) {
                header('Location: /users/me/activities');
                $html = $this->renderer->render('users/vendors/activities/list');
                $this->response->setContent($html);
                return;
            }

            header('Location: /');
            $html = $this->renderer->render('activities/list');
            $this->response->setContent($html);
        }

        // More than one user found - fatal error
        if (count($users) > 1) {
            throw new Exception('More than one user found with the same email');
            return;
        }

        // User error cases: either wrong password, or user not found
        // TODO: error message on login page
        $data = [

        ];
        $html = $this->renderer->render('users/login', $data);
        $this->response->setContent($html);
    }

    public function logout()
    {
        session_destroy();
        $_SESSION = [];
        header('Location: /');

        $html = $this->renderer->render('activities/list');
        $this->response->setContent($html);
    }
}