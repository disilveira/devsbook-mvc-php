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

    public static function getHomeFeed($idUser, $page)
    {
        $itemsPerPage = 2;

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

        $posts = [];
        foreach ($postList as $postItem) {
            $post = new Post();
            $post->id = $postItem['id'];
            $post->type = $postItem['type'];
            $post->created_at = $postItem['created_at'];
            $post->body = $postItem['body'];
            $post->mine = false;

            if ($postItem['id_user'] == $idUser) {
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

        return [
            'posts' => $posts,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }
}
