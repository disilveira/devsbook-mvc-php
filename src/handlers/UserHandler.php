<?php

namespace src\handlers;

use \src\models\User;
use \src\models\UserRelation;
use \src\handlers\PostHandler;

class UserHandler
{
    public static function checkLogin()
    {
        if (!empty($_SESSION['devsbook']['token'])) {
            $token = $_SESSION['devsbook']['token'];

            $data = User::select()->where('token', $token)->one();

            if (count($data) > 0) {

                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->name = $data['name'];
                $loggedUser->avatar = $data['avatar'];

                return $loggedUser;
            }
        }

        return false;
    }

    public static function verifyLogin($email, $password)
    {
        $user = User::select()->where('email', $email)->one();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $token = md5(time() . rand(0, 9999) . time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                    ->execute();

                return $token;
            }
        }
        return false;
    }

    public static function idExists($id)
    {
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    public static function emailExists($email)
    {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function getUser($id, $full = false)
    {
        $data = User::select()->where('id', $id)->one();


        if ($data) {
            $user = new User();
            $user->id = $data['id'];
            $user->name = $data['name'];
            $user->birthdate = $data['birthdate'];
            $user->city = $data['city'];
            $user->work = $data['work'];
            $user->avatar = $data['avatar'];
            $user->cover = $data['cover'];

            if ($full) {
                $user->followers = [];
                $user->following = [];
                $user->photos = [];

                // Usuários que eu sigo
                $followers = UserRelation::select()->where('user_to', $id)->get();
                foreach ($followers as $follower) {
                    $followerData = User::select()->where('id', $follower['user_from'])->one();
                    $followerInfo = new User();
                    $followerInfo->id = $followerData['id'];
                    $followerInfo->name = $followerData['name'];
                    $followerInfo->avatar = $followerData['avatar'];

                    $user->followers[] = $followerInfo;
                }

                // Usuário que me segue
                $following = UserRelation::select()->where('user_from', $id)->get();
                foreach ($following as $follower) {
                    $followerData = User::select()->where('id', $follower['user_to'])->one();
                    $followerInfo = new User();
                    $followerInfo->id = $followerData['id'];
                    $followerInfo->name = $followerData['name'];
                    $followerInfo->avatar = $followerData['avatar'];

                    $user->following[] = $followerInfo;
                }

                // Photos
                $user->photos = PostHandler::getPhotosFrom($id);
            }

            return $user;
        }

        return false;
    }

    public static function addUser($name, $email, $password, $birthdate)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time() . rand(0, 9999) . time());

        User::insert([
            'email' => $email,
            'password' => $hash,
            'name' => $name,
            'birthdate' => $birthdate,
            'token' => $token
        ])->execute();

        return $token;
    }
}
