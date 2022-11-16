<?php

namespace src\handlers;

use \src\models\Post;
use \src\models\PostComment;
use \src\models\User;
use \src\models\UserRelation;
use \src\models\PostLike;

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

            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();


            $post->likeCount = count($likes);
            $post->liked = self::isLiked($postItem['id'], $loggedUserId);

            $post->comments = PostComment::select()->where('id_post', $postItem['id'])->get();
            foreach ($post->comments as $key => $comment) {
                $post->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $post;
        }

        return $posts;
    }

    public static function isLiked($id, $loggedUserId)
    {
        $isLiked = PostLike::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
            ->get();

        if (count($isLiked) > 0) {
            return true;
        }

        return false;
    }

    public static function deleteLike($id, $loggedUserId)
    {
        PostLike::delete()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
            ->execute();
    }

    public static function addLike($id, $loggedUserId)
    {
        PostLike::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId
        ])->execute();
    }

    public static function addComment($id, $text, $loggedUserId)
    {
        PostComment::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'body' => $text
        ])->execute();
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

    public static function delete($idPost, $loggedUserId)
    {
        //Verifica se o post existe e se é meu
        $post = Post::select()
            ->where('id', $idPost)
            ->where('id_user', $loggedUserId)
            ->get();

        if (count($post) > 0) {
            $post = $post[0];

            //Deleta os likes e comentários
            PostLike::delete()->where('id_post', $idPost)->execute();
            PostComment::delete()->where('id_post', $idPost)->execute();

            //Se o type for igual a photo, deletar o arquivo
            if ($post['type'] === 'photo') {
                $img = __DIR__ . '/../../public/media/uploads/' . $post['body'];
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            //Deletar o post
            Post::delete()->where('id', $idPost)->execute();
        }
    }
}
