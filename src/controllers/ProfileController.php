<?php
namespace src\controllers;

use \core\Controller;
use DateTime;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false)
        {
            $this->redirect('/login');
        }
    }

    public function index($atts = [])
    {
        // Página atual
        $page = intval(filter_input(INPUT_GET, 'page'));

        // Id do usuário logado
        $id = $this->loggedUser->id;

        // Se o array $atts['id'] não está vazio, substitui o valor da variável $id para $atts['id'].
        if(!empty($atts['id'])) {
            $id = intval($atts['id']);
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);
        // Se não achou usuário ou retornou falso o getUser($id), redirect para página inicial.
        if(!$user) {
            $this->redirect('/');
        }

        // Pegar a idade
        $dateFrom = new \DateTime($user->birthdate); // Data que o usuário nasceu
        $dateTo = new \DateTime('today'); // Data de hoje
        $user->ageYears = $dateFrom->diff($dateTo)->y; // Pegamos a diferenca entre a data de nascimento e o dia hoje.

        // Pegando o feed do usuário, pode ser o usuário logado ou o usuário $id.
        $feed = PostHandler::getUserFeed($id, $page, $this->loggedUser->id);

        // Verificar se o usuário logado segue o usuário $id
        $isFollowing = false;
        if($user->id != $this->loggedUser->id)
        {
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

        // Se o perfil existir
        if(UserHandler::idExists($to))
        {
            if(UserHandler::isFollowing($this->loggedUser->id, $to))
            {
                // Deixar de Seguir
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else
            {
                // Seguir
                UserHandler::follow($this->loggedUser->id, $to);
            }
        }

        $this->redirect('/perfil/'.$to);
    }

    public function friends($atts = [])
    {
        // Id do usuário logado
        $id = $this->loggedUser->id;

        // Se o array $atts['id'] não está vazio, substitui o valor da variável $id para $atts['id'].
        if(!empty($atts['id'])) {
            $id = intval($atts['id']);
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);
        // Se não achou usuário ou retornou falso o getUser($id), redirect para página inicial.
        if(!$user) {
            $this->redirect('/');
        }

        // Pegar a idade
        $dateFrom = new \DateTime($user->birthdate); // Data que o usuário nasceu
        $dateTo = new \DateTime('today'); // Data de hoje
        $user->ageYears = $dateFrom->diff($dateTo)->y; // Pegamos a diferenca entre a data de nascimento e o dia hoje.

        // Verificar se o usuário logado segue o usuário $id
        $isFollowing = false;
        if($user->id != $this->loggedUser->id)
        {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

}