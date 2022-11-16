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

    public function upload()
    {
        $array = ['error' => ''];

        if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
            $photo = $_FILES['photo'];

            $maxWidth = 800;
            $maxHeight = 800;

            if (in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {
                list($widthOrigi, $heightOrig) = getimagesize($photo['tmp_name']);
                $ratio = $widthOrigi / $heightOrig;

                $newWidth = $maxWidth;
                $newHeight = $maxHeight;

                $ratioMax = $maxWidth / $maxHeight;

                if ($ratioMax > $ratio) {
                    $newWidth = $newHeight * $ratio;
                } else {
                    $newHeight = $newWidth / $ratio;
                }

                $finalImage = imagecreatetruecolor($newWidth, $newHeight);

                switch ($photo['type']) {
                    case 'image/png':
                        $image = imagecreatefrompng($photo['tmp_name']);
                        break;
                    case 'image/jpg':
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($photo['tmp_name']);
                        break;
                }

                imagecopyresampled(
                    $finalImage,
                    $image,
                    0,
                    0,
                    0,
                    0,
                    $newWidth,
                    $newHeight,
                    $widthOrigi,
                    $heightOrig
                );

                $photoName = md5(time() . rand(0, 9999)) . '.jpg';


                imagejpeg($finalImage, 'media/uploads/' . $photoName);

                PostHandler::addPost(
                    $this->loggedUser->id,
                    'photo',
                    $photoName
                );
            }
        } else {
            $array['error'] = 'Nenhuma imagem enviada!';
        }

        header("Content-Type: application/json");
        echo json_encode($array);
        exit;
    }
}
