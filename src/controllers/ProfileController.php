<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class ProfileController extends Controller
{

    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser == false) {
            $this->redirect('/signin');
        }
    }

    public function index($props = [])
    {

        $id = $this->loggedUser->id;

        if (!empty($props['id'])) {
            $id = $props['id'];
        }

        $user = UserHandler::getUser($id);

        if (!$user) {
            $this->redirect('/');
        }



        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user
        ]);
    }
}