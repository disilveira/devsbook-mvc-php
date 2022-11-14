<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class LoginController extends Controller
{
    public function signin()
    {
        $flash = '';

        if (!empty($_SESSION['devsbook']['flash'])) {
            $flash = $_SESSION['devsbook']['flash'];
            $_SESSION['devsbook']['flash'] = '';
        }

        $this->render('signin', [
            'flash' => $flash
        ]);
    }

    public function signinPost()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if ($email && $password) {

            $token = UserHandler::verifyLogin($email, $password);

            if ($token) {
                $_SESSION['devsbook']['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['devsbook']['flash'] = "E-mail e/ou senha não conferem.";
                $this->redirect('/signin');
            }
        }

        $this->redirect('/signin');
    }

    public function signup()
    {
        $flash = '';

        if (!empty($_SESSION['devsbook']['flash'])) {
            $flash = $_SESSION['devsbook']['flash'];
            $_SESSION['devsbook']['flash'] = '';
        }

        $this->render('signup', [
            'flash' => $flash
        ]);
    }

    public function signupPost()
    {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if ($name && $email && $password && $birthdate) {

            if (UserHandler::emailExists($email) === false) {
                $token = UserHandler::addUser($name, $email, $password, $birthdate);
                $_SESSION['devsbook']['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['devsbook']['flash'] = "E-mail já cadastrado!";
                $this->redirect('/signup');
            }
        }

        $this->redirect('/signup');
    }

    public function logout()
    {
        $_SESSION['devsbook']['token'] = '';
        $this->redirect('/signin');
    }
}
