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
        //! FALTA MAIS VERIFICAÇÕES
        $email = $this->request->post('email');
        $password = $this->request->post('password');
        $user = User::login($email, $password);
        if ($user) {
            $this->response->setCookie('user_id', $user->getId());
            //redireciona do Login para a página do profile
            $this->response->redirect('/users/profile');
        } else {
            $this->response->setContent('Invalid email or password');
            $this->response->setStatusCode(401);
            }
    }

    public function logout()
    {
        //Redirecionar para a página Login?
    }
}