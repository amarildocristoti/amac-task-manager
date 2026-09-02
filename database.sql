-- ============================================================
-- Banco de dados do exemplo "Lista de Tarefas".
-- Rode este script inteiro no seu MySQL (via phpMyAdmin ou
-- linha de comando) antes de testar a aplicação.
-- ============================================================

CREATE DATABASE IF NOT EXISTS meu_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE meu_app;

-- Uma unica tabela, ja que este exemplo tem uma unica entidade.
-- Repare que NAO ha coluna "user_id" -- este exemplo nao tem
-- login, entao todas as tarefas sao "globais" (compartilhadas).
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,           -- o texto da tarefa
    done TINYINT(1) NOT NULL DEFAULT 0,    -- 0 = pendente, 1 = concluida
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
