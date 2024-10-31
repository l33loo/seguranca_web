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
        //Lógica de verificação do login
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        if (empty($email) || empty($password)) {
            $this->response->setContent('Email and password are required');
            $this->response->setStatusCode(400);
            return;
        }
        try {
            $user = User::login($email, $password);
            if ($user) {
                $this->response->setCookie('id', (string) $user->getId());
                // Redireciona do login para a página de profile
                $this->response->redirect('/users/profile');
            } else {
                $this->response->setContent('Invalid email or password');
                $this->response->setStatusCode(401);
            }
        } catch (\Exception $e) {
            // Captura e trata exceções, caso ocorram
            $this->response->setContent('An error occurred during login');
            $this->response->setStatusCode(500);
        }
    }

    public function logout()
    {
        //Redirecionar para a página Login?
    }
}