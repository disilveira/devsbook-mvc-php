<?php

namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;

class PostHandler
{
    public static function addPost($idUser, $type, $body)
    {
        $body = trim($body);
        if (!empty($idUser) && !empty($body)) {
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'body' => $body
            ])->execute();
        }
    }

    public static function _postListToObject($postList, $loggedUserId)
    {
        $posts = [];
        foreach ($postList as $postItem) {
            $post = new Post();
            $post->id = $postItem['id'];
            $post->type = $postItem['type'];
            $post->created_at = $postItem['created_at'];
            $post->body = $postItem['body'];
            $post->mine = false;

            if ($postItem['id_user'] == $loggedUserId) {
                $post->mine = true;
            }

            $postUser = User::select()->where('id', $postItem['id_user'])->one();
            $post->user = new User();
            $post->user->id = $postUser['id'];
            $post->user->name = $postUser['name'];
            $post->user->avatar = $postUser['avatar'];

            $post->likeCount = 0;
            $post->liked = false;
            $post->comments = [];

            $posts[] = $post;
        }

        return $posts;
    }

    public static function getUserfeed($idUser, $page, $loggedUserId)
    {
        $itemsPerPage = 5;

        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $itemsPerPage)
            ->get();

        $totalPosts = Post::select()
            ->where('id_user', $idUser)
            ->count();

        $totalPages = ceil($totalPosts / $itemsPerPage);

        $posts = self::_postListToObject($postList, $loggedUserId);

        return [
            'posts' => $posts,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }

    public static function getHomeFeed($idUser, $page)
    {
        $itemsPerPage = 5;

        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];

        foreach ($userList as $usersItem) {
            $users[] = $usersItem['user_to'];
        }

        $users[] = $idUser;

        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $itemsPerPage)
            ->get();

        $totalPosts = Post::select()
            ->where('id_user', 'in', $users)
            ->count();

        $totalPages = ceil($totalPosts / $itemsPerPage);

        $posts = self::_postListToObject($postList, $idUser);

        return [
            'posts' => $posts,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }

    public static function getPhotosFrom($idUser)
    {
        $photosData = Post::select()->where('id_user', $idUser)->where('type', 'photo')->get();

        $photos = [];

        foreach ($photosData as $photo) {
            $photoInfo = new Post();
            $photoInfo->id = $photo['id'];
            $photoInfo->type = $photo['type'];
            $photoInfo->created_at = $photo['created_at'];
            $photoInfo->body = $photo['body'];

            $photos[] = $photoInfo;
        }

        return $photos;
    }
}
