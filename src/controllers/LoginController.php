<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;

class LoginController extends Controller
{
    public function signin()
    {
        $flash = '';

        if (!empty($_SESSION['devsbook']['flash'])) {
            $flash = $_SESSION['devsbook']['flash'];
            $_SESSION['devsbook']['flash'] = '';
        }

        $this->render('login', [
            'flash' => $flash
        ]);
    }

    public function signinPost()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if ($email && $password) {

            $token = LoginHandler::verifyLogin($email, $password);

            if ($token) {
                $_SESSION['devsbook']['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['devsbook']['flash'] = "E-mail e/ou senha não conferem.";
                $this->redirect('/login');
            }
        } else {
            $this->redirect('/login');
        }
    }

    public function signup()
    {
        echo 'Tela de cadastro';
    }
}
