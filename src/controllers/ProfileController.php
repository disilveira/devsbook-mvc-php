<?php

namespace src\controllers;

use \core\Controller;
use src\handlers\PostHandler;
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

        $page = intval(filter_input(INPUT_GET, 'page'));

        $id = $this->loggedUser->id;

        if (!empty($props['id'])) {
            $id = $props['id'];
        }

        $user = UserHandler::getUser($id, true);

        if (!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->agerYears = $dateFrom->diff($dateTo)->y;

        $feed = PostHandler::getUserfeed($id, $page, $this->loggedUser->id);

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed
        ]);
    }
}
