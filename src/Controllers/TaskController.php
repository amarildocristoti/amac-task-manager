<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Models\Task;

/**
 * ============================================================
 * TASKCONTROLLER -- exemplo completo de Controller simples.
 * ============================================================
 *
 * 4 acoes, cada uma ligada a uma rota (veja public/index.php):
 *   index()  -> GET  /              lista as tarefas
 *   store()  -> POST /tasks/store   cria uma tarefa nova
 *   toggle() -> POST /tasks/toggle/{id}  marca/desmarca como feita
 *   delete() -> POST /tasks/delete/{id}  remove uma tarefa
 *
 * Repare no padrao repetido em store() e delete(): SEMPRE validar
 * o token CSRF antes de processar um POST que muda dados.
 */
class TaskController extends Controller
{
    private Task $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    public function index(): void
    {
        $tasks = $this->taskModel->all();

        // 'tasks' e 'csrf_token' viram variaveis $tasks e $csrf_token
        // dentro da view graças ao extract() (veja Traits/RendersView.php)
        $this->view('tasks/index', [
            'tasks'      => $tasks,
            'csrf_token' => Security::generateCsrfToken(),
        ]);
    }

    public function store(): void
    {
        if (!Security::validateCsrfToken($this->input('csrf_token'))) {
            $_SESSION['error'] = 'Token de segurança inválido.';
            $this->redirect('/');
        }

        $title = $this->input('title');

        if (empty($title)) {
            $_SESSION['error'] = 'Digite uma tarefa.';
            $this->redirect('/');
        }

        $this->taskModel->create($title);
        $this->redirect('/'); // Sempre volta pra listagem depois de uma acao.
    }

    /**
     * Nao precisa de validacao de CSRF tao rigorosa aqui porque
     * so alterna um booleano (baixo risco), mas em geral prefira
     * validar em TODO POST -- fizemos assim aqui de proposito para
     * voce ver que o toggle funciona mesmo sem token, e comparar
     * com store()/delete() que SEMPRE validam.
     */
    public function toggle(string $id): void
    {
        $this->taskModel->toggleDone((int) $id);
        $this->redirect('/');
    }

    public function delete(string $id): void
    {
        if (!Security::validateCsrfToken($this->input('csrf_token'))) {
            $_SESSION['error'] = 'Token de segurança inválido.';
            $this->redirect('/');
        }

        $this->taskModel->delete((int) $id);
        $this->redirect('/');
    }
}
