<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Booking\User;
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
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $user = User::find($id);
        if ($user && password_verify($password, $user->password)) {
            $this->response->redirect('/users/profile');
        } else {
            $this->response->redirect('/users/login');
        }
        $html = $this->renderer->render('/activities/list');
        $this->response->setContent($html);
    }

    public function logout()
    {
        //Redirecionar para a página Login?
    }
}