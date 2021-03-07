<?php
// Continuar da aula: Fotos.
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/cadastro', 'LoginController@signup');
$router->post('/cadastro', 'LoginController@signupAction');

$router->post('/post/new', 'PostController@new'); // Adicionar post

$router->get('/perfil/{id}/amigos', 'ProfileController@friends'); // Amigos
$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@index'); // Rota do perfil do usuário id
$router->get('/perfil', 'ProfileController@index'); // Rota do perfil do usuário logado

$router->get('/amigos', 'ProfileController@friends');

$router->get('/sair', 'LoginController@logout'); // logout