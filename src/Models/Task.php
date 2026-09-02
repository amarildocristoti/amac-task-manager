<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * ============================================================
 * MODEL TASK -- unica classe que fala com a tabela "tasks".
 * ============================================================
 *
 * Regra do MVC: SO o Model executa SQL. O Controller nunca
 * escreve queries -- so chama estes metodos.
 *
 * Este e o exemplo mais simples possivel de Model: 5 metodos
 * cobrindo o CRUD completo (Create, Read, Update "toggle", Delete),
 * todos usando prepared statements (protecao contra SQL Injection).
 *
 * Para adaptar isto a outra entidade (ex: Note, Contact, Expense):
 * copie este arquivo, troque o nome da classe, o nome da tabela
 * nas queries, e os campos ("title" viraria outra coisa).
 */
class Task
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Lista todas as tarefas: pendentes primeiro (done ASC),
     * mais recentes primeiro dentro de cada grupo (id DESC).
     */
    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM tasks ORDER BY done ASC, id DESC');
        return $stmt->fetchAll();
    }

    /** Busca uma tarefa especifica pelo ID (usado antes de editar/excluir). */
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /** Cria uma tarefa nova. Retorna o ID gerado. */
    public function create(string $title): int
    {
        $stmt = $this->db->prepare('INSERT INTO tasks (title, created_at) VALUES (:title, NOW())');
        $stmt->execute(['title' => $title]);
        return (int) $this->db->lastInsertId();
    }

    /** Atualiza so o texto da tarefa. */
    public function update(int $id, string $title): bool
    {
        $stmt = $this->db->prepare('UPDATE tasks SET title = :title WHERE id = :id');
        return $stmt->execute(['title' => $title, 'id' => $id]);
    }

    /**
     * Inverte o status concluida/pendente com uma unica query
     * (NOT done troca 0 por 1 e vice-versa direto no banco,
     * sem precisar ler o valor atual antes).
     */
    public function toggleDone(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE tasks SET done = NOT done WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /** Remove a tarefa definitivamente. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
