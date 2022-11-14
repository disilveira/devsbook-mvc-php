<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class AjaxController extends Controller
{

    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser == false) {
            header("Content-Type: application/json");
            echo json_encode([
                'error' => 'Usuário não logado no sistema',

            ]);
            exit;
        }
    }

    public function like($atts)
    {
        $id = $atts['id'];

        if (PostHandler::isLiked($id, $this->loggedUser->id)) {
            PostHandler::deleteLike($id, $this->loggedUser->id);
        } else {
            PostHandler::addLike($id, $this->loggedUser->id);
        }
    }

    public function comment()
    {

        $array = ['error' => ''];

        $id = intval(filter_input(INPUT_POST, 'id'));
        $text = filter_input(INPUT_POST, 'txt');

        if ($id && $text) {
            PostHandler::addComment($id, $text, $this->loggedUser->id);

            $array['link'] = '/profile/' . $this->loggedUser->id;
            $array['avatar'] = '/media/avatars/' . $this->loggedUser->avatar;
            $array['name'] = $this->loggedUser->name;
            $array['body'] = $text;
        }

        header("Content-Type: application/json");
        echo json_encode($array);
        exit;
    }
}
