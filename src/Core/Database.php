<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * ============================================================
 * DATABASE.PHP -- conexao unica (Singleton) com o banco MySQL.
 * ============================================================
 *
 * QUANDO USAR: dentro de QUALQUER Model, chame
 *   $this->db = Database::getConnection();
 * no construtor, e use $this->db->prepare(...) normalmente.
 *
 * Voce nunca precisa mexer neste arquivo -- ele e 100% generico
 * e funciona igual para qualquer projeto/tabela.
 *
 * Por que "Singleton"? Nao importa quantas vezes voce criar um
 * "new Model()" durante a mesma requisicao, a conexao com o
 * banco e aberta so UMA vez e reaproveitada -- economiza recursos.
 */
class Database
{
    /** Guarda a unica conexao PDO da aplicacao (comeca vazia). */
    private static ?PDO $instance = null;

    /** Construtor privado: ninguem pode fazer "new Database()" de fora. */
    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

            $options = [
                // Lanca excecao em vez de falhar silenciosamente -- facilita debugar.
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // SELECTs retornam array associativo (['coluna' => valor]) por padrao.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Forca prepared statements REAIS do MySQL (protecao extra contra SQL Injection).
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Nunca exponha o erro real do banco para o usuario final.
                error_log('Erro de conexao com o banco: ' . $e->getMessage());
                die('Erro ao conectar com o banco de dados.');
            }
        }

        return self::$instance;
    }

    /** Impede clonar a instancia (garante que so existe UMA conexao). */
    private function __clone() {}
}
