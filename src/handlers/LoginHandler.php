<?php

namespace src\handlers;

use \src\models\User;

class LoginHandler
{
    public static function checkLogin()
    {
        if (!empty($_SESSION['devsbook']['token'])) {
            $token = $_SESSION['devsbook']['token'];

            $data = User::select()->where('token', $token)->one();

            if (count($data) > 0) {

                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name'];

                return $loggedUser;
            }
        }

        return false;
    }
}
