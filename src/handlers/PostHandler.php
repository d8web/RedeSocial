<?php

namespace src\handlers;
use \src\models\Post;
use \src\models\User;
use \src\models\UserRelation;
use \src\models\PostLike;
use \src\models\PostComment;

class PostHandler
{
    public static function addPost(int $idUser, string $type, $body)
    {
        $body = trim($body);
        
        if(!empty($idUser) && !empty($body))
        {
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body
            ])->execute();
        }
    }

    // Return Objeto
    public static function _postListToObject($postList, $loggedUserId)
    {
        $posts = [];

        foreach($postList as $postItem)
        {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->created_at = $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;

            if($postItem['id_user'] == $loggedUserId) {
                $newPost->mine = true;
            }

            // Preencher informações do usuário do post
            $newUser = User::select()->where('id', $postItem['id_user'])->one();

            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();
            // TO DO: Prencher informações de like
            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLiked($postItem['id'], $loggedUserId);

            // TO DO: Preencher informações de comments
            $newPost->comments = PostComment::select()->where('id_post', $postItem['id'])->get();
            foreach($newPost->comments as $key => $comment)
            {
                // Adicionando informações do usuário que fez o comentário.
                $newPost->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $newPost;
        }

        return $posts;
    }

    public static function isLiked($id, $loggedUserId)
    {
        $myLike = PostLike::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($myLike) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUserFeed($idUser, $page, $loggedUserId)
    {
        $perPage = 5;

        // Pegar os posts desses amigos ordenado pela data
        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', $idUser)
        ->count();
        $pageCount = ceil($total / $perPage);

        // Transformar em objeto
        $posts = self::_postListToObject($postList, $loggedUserId);

        // Retornar a lista de posts
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getHomeFeed($idUser, $page)
    {
        $perPage = 5;

        // Pegar lista de usuários que eu sigo, o algoritmo vai mostrar apenas os posts dos amigos do usuário logado.
        $userList = UserRelation::select()
            ->where('user_from', $idUser)
        ->get();

        $users = [];
        foreach($userList as $userItem)
        {
            $users[] = $userItem['user_to'];
        }
        $users[] = $idUser;

        // Pegar os posts desses amigos ordenado pela data
        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
        ->count();
        $pageCount = ceil($total / $perPage);

        // Transformar em objeto
        $posts = self::_postListToObject($postList, $idUser);

        // Retornar a lista de posts
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getPhotosFrom($idUser)
    {
        $photosData = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
            ->orderBy('created_at', 'desc')
        ->get();

        $photos = [];
        foreach($photosData as $photo)
        {
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->created_at = $photo['created_at'];
            $newPost->body = $photo['body'];

            $photos[] = $newPost;
        }

        return $photos;

    }

    public static function deleteLike($idPost, $loggedUserId)
    {
        PostLike::delete()
            ->where('id_post', $idPost)
            ->where('id_user', $loggedUserId)
        ->execute();
    }

    public static function insertLike($idPost, $loggedUserId)
    {
        PostLike::insert([
            'id_post' => $idPost,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static function addComment($id, $txt, $loggedUserId)
    {
        PostComment::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s'),
            'body' => $txt
        ])->execute();
    }

    public static function deletePost($id, $loggedUserId)
    {
        // Verificar se o post existe e se é do usuário logado.
        $post = Post::select()
            ->where('id', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($post) > 0) {
            $post = $post[0];
            // Deletar os likes e comentários
            PostLike::delete()->where('id_post', $id)->execute();
            PostComment::delete()->where('id_post', $id)->execute();

            // Se o tipo do post for foto, deletar o arquivo da pasta media
            if($post['type'] === 'photo')
            {
                // echo __DIR__;
                $img = __DIR__.'/../../public/media/uploads/'.$post['body'];
                if(file_exists($img))
                {
                    // Delete Image
                    unlink($img);
                }
            }

             // Deletar o post
             Post::delete()->where('id', $id)->execute();
        }
    }

}