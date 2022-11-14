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

    public function index($atts = [])
    {

        $page = intval(filter_input(INPUT_GET, 'page'));

        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando Informações do Usuário
        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->agerYears = $dateFrom->diff($dateTo)->y;

        // Pegando o feed do usuário
        $feed = PostHandler::getUserfeed($id, $page, $this->loggedUser->id);

        // Verificar se sigo o usuário

        $isFollowing = false;

        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    public function follow($atts)
    {
        $to = intval($atts['id']);

        if (UserHandler::idExists($to)) {

            if (UserHandler::isFollowing($this->loggedUser->id, $to)) {
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else {
                UserHandler::follow($this->loggedUser->id, $to);
            }
        }

        $this->redirect('/profile/' . $to);
    }

    public function friends($atts = [])
    {
        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando Informações do Usuário
        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        // Verificar se sigo o usuário
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile-friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

    public function photos($atts = [])
    {
        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando Informações do Usuário
        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        // Verificar se sigo o usuário
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile-photos', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }
}
