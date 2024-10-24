<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\Activity;
use App\Template\FrontendRenderer;
use App\DB\MySQLConnect;

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
        try {
            $db = MySQLConnect::getInstance();
            $stmt = $db->escapedSelectQuery('SELECT * FROM activity;');
            $objects = $stmt->fetchAll(\PDO::FETCH_CLASS, Activity::class);
            $data['activities'] = $objects;
            // print_r($stmt->fetchAll());  
            // $results = [];
            // while($row = $stmt->fetchObject(Activity::class)) {
            //     $results[] = $row;
            // }
            // $data['activities'] = $results;
        } catch(\PDOException $e) {
            echo 'ERROR <3: ' . $e->getMessage();
        }

        $html = $this->renderer->render('activities/list', $data);
        $this->response->setContent($html);
    }

    public function show($params)
    {
        $data = [
            'id' => $params['id'],
        ];
        
        $html = $this->renderer->render('activities/show', $data);
        $this->response->setContent($html);
    }

    public function create()
    {
        // If VENDOR
    }

    public function update()
    {
        // IF VENDOR
    }

    public function archive()
    {
        // If VENDOR
    }

    public function listReservations($params): void
    {
        // If VENDOR
        $data = [
            'activityId' => $params['id'],
        ];

        $html = $this->renderer->render('reservations/list', $data);
        $this->response->setContent($html);
    }
}