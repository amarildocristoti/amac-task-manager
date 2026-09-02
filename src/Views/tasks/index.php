<?php
/**
 * View da lista de tarefas. Recebe $tasks (array de tarefas) e
 * $csrf_token (string) do TaskController::index().
 *
 * Estrutura: formulario para adicionar no topo, lista logo abaixo.
 * Cada linha da lista tem seu PROPRIO <form> para marcar/excluir --
 * isso e proposital: HTML puro nao tem como enviar um POST via
 * clique simples sem um form, entao cada acao (toggle, delete)
 * precisa do seu formulario individual.
 */
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <h3 class="mb-4"><i class="bi bi-check2-square me-2"></i>Minha Lista de Tarefas</h3>

        <!-- Formulário para adicionar nova tarefa -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="<?= APP_URL ?>/tasks/store" class="d-flex gap-2">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="text" name="title" class="form-control" placeholder="Nova tarefa..." required autofocus>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de tarefas -->
        <div class="card">
            <div class="card-body p-0">
                <?php if (empty($tasks)): ?>
                    <!-- "Empty state": mensagem amigável quando não há dados,
                         em vez de simplesmente mostrar uma lista vazia -->
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2 mb-0">Nenhuma tarefa ainda. Adicione uma acima.</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($tasks as $task): ?>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Botão-checkbox: um form pequeno que só alterna done -->
                                    <form method="POST" action="<?= APP_URL ?>/tasks/toggle/<?= $task['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-link p-0 border-0" style="font-size:1.3rem;">
                                            <?php if ($task['done']): ?>
                                                <i class="bi bi-check-square-fill text-success"></i>
                                            <?php else: ?>
                                                <i class="bi bi-square text-muted"></i>
                                            <?php endif; ?>
                                        </button>
                                    </form>

                                    <!-- htmlspecialchars() SEMPRE ao exibir texto do usuário (protege contra XSS) -->
                                    <span class="<?= $task['done'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>

                                <form method="POST" action="<?= APP_URL ?>/tasks/delete/<?= $task['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
