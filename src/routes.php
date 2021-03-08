<?php
// Continuar da aula: Upload de Fotos (1/3).
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/cadastro', 'LoginController@signup');
$router->post('/cadastro', 'LoginController@signupAction');

$router->post('/post/new', 'PostController@new'); // Adicionar post

$router->get('/perfil/{id}/fotos', 'ProfileController@photos');
$router->get('/perfil/{id}/amigos', 'ProfileController@friends'); // Amigos
$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@index'); // Rota do perfil do usuário id
$router->get('/perfil', 'ProfileController@index'); // Rota do perfil do usuário logado

$router->get('/amigos', 'ProfileController@friends'); // Amigos
$router->get('/fotos', 'ProfileController@photos'); // Fotos

$router->get('/pesquisa', 'SearchController@index');

$router->get('/config', 'ConfigController@index');
$router->post('/config', 'ConfigController@save');

$router->get('/sair', 'LoginController@logout'); // logout

$router->get('/ajax/like/{id}', 'AjaxController@like');

$router->post('/ajax/comment', 'AjaxController@comment'); // Adicionar comentário