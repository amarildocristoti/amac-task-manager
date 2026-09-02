<?php

/**
 * ============================================================
 * FRONT CONTROLLER do exemplo "Lista de Tarefas".
 * ============================================================
 *
 * Compare com o public/index.php do esqueleto puro: a unica
 * diferenca real e a lista de rotas abaixo. Isso mostra bem o
 * que muda de projeto para projeto (as rotas + os Controllers/
 * Models/Views por tras delas) e o que NAO muda (tudo em Core/).
 */

require_once __DIR__ . '/../config/config.php';

use App\Core\Router;

$router = new Router();

// GET  /                    -> mostra a lista de tarefas
// POST /tasks/store          -> cria uma tarefa nova
// POST /tasks/toggle/{id}    -> marca/desmarca como concluida
// POST /tasks/delete/{id}    -> remove uma tarefa
$router->get('/', 'TaskController@index');
$router->post('/tasks/store', 'TaskController@store');
$router->post('/tasks/toggle/{id}', 'TaskController@toggle');
$router->post('/tasks/delete/{id}', 'TaskController@delete');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
