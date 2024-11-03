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
        $html = $this->renderer->render('activities/new');
        $this->response->setContent($html);
    }

    public function create()
    {
        $name = $this->request->getParameter('name');
        $description = $this->request->getParameter('description');
        $date = $this->request->getParameter('date');
        $time = $this->request->getParameter('time');
        $cost = $this->request->getParameter('cost');

        $activity = new Activity(
            $name,
            $description,
            $date,
            $time,
            floatval($cost),
            \App\Booking\User::getLoggedUserId()
        );
        
        $bodyParams = $this->request->getParameters();
        $validOrErrors = Activity::validateForm($bodyParams);
        $data = [];

        if ($validOrErrors === true) {
            $activity->save();
            $data['success'] = 'New activity #' . $activity->getId() . ' was created successfully.';
            header('Location: /users/me/activities');
            $html = $this->renderer->render('users/vendors/activities/list', $data);
            $this->response->setContent($html);
        } else {
            $data['errors'] = $validOrErrors;
            $data['error'] = 'There was an error creating the activity.';
            $html = $this->renderer->render('activities/new', $data);
            $this->response->setContent($html);
        }
    }

    public function editForm($params)
    {
        $activity = Activity::find(intval($params['activityId']));
      
        if ($activity->hasPassed() || $activity->getIsarchived()) {
            // TODO: add error
            header('Location: /users/me/activities');
            $userController = new UserController($this->request, $this->response, $this->renderer);
            $userController->vendorActivities();
            return;
        }
        
        $data = [
            'activity' => $activity,
        ];

        $html = $this->renderer->render('activities/edit', $data);
        $this->response->setContent($html);
    }

    public function edit($params)
    {
        $activity = Activity::find(intval($params['activityId']));
        if ($activity->hasPassed() || $activity->getIsarchived()) {
            // TODO: add error
            header('Location: /users/me/activities');
            $userController = new UserController($this->request, $this->response, $this->renderer);
            $userController->vendorActivities();
            return;
        }

        $name = $this->request->getParameter('name');
        $description = $this->request->getParameter('description');
        $date = $this->request->getParameter('date');
        $time = $this->request->getParameter('time');
        $cost = $this->request->getParameter('cost');
        
        $activity
            ->setName($name)
            ->setDescription($description)
            ->setDate($date)
            ->setTime($time)
            ->setCost(floatval($cost));
     
        $data = [
            'activity' => $activity,
        ];

        $bodyParams = $this->request->getParameters();
        $validOrErrors = Activity::validateForm($bodyParams);
        if ($validOrErrors === true) {
            $activity->save();
            $data['success'] = 'The activity was updated successfully.';
        } else {
            $data['error'] = 'There was an error updating the activity.';
            $data['errors'] = $validOrErrors;
        }

        // header('Location: /activities/' . $activity->getId() . '/edit');
        $html = $this->renderer->render('activities/edit', $data);
        $this->response->setContent($html);
    }

    public function archive($params)
    {
        $archiveParam = $this->request->getParameter('archive');
        if (isset($archiveParam)) {
            $activity = Activity::find(intval($params['activityId']));
            $activity->setIsarchived(true)->save();
        }

        header('Location: /users/me/activities');
        $html = $this->renderer->render('users/vendors/activities/list');
        $this->response->setContent($html);
    }

    public function delete($params)
    {
        $deleteParam = $this->request->getParameter('delete');
        if (isset($deleteParam)) {
            $activity = Activity::find(intval($params['activityId']));
            $activity->loadReservations();

            if (count($activity->getReservations()) > 0) {
                // TODO send error message
            }

            if (count($activity->getReservations()) === 0) {
                $activity->delete();
            }
        }

        header('Location: /users/me/activities');
        $html = $this->renderer->render('users/vendors/activities/list');
        $this->response->setContent($html);
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
        $activity = Activity::find(intval($params['activityId']));
        if ($activity->hasPassed() || $activity->getIsarchived()) {
            // TODO: add error, cannot post comment
            $this->show($params);
            return;
        }
        $newComment = new \App\Booking\Comment(
            $this->request->getBodyParameters()['comment'],
            \App\Booking\User::getLoggedUserId(),
            intval($params['activityId'])
        );

        $newComment->save();
        $this->show($params);
    }
}