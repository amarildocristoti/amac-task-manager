<?php

/**
 * ============================================================
 * CONFIG.PHP -- carregado UMA VEZ, no topo do public/index.php.
 * ============================================================
 *
 * Responsabilidades deste arquivo:
 *   1) Ler o arquivo .env e transformar cada linha numa constante
 *      PHP (DB_HOST, APP_URL, etc.) que o resto do sistema usa.
 *   2) Configurar exibicao de erros conforme o ambiente.
 *   3) Iniciar a sessao PHP (necessaria para login, mensagens
 *      flash de erro/sucesso, CSRF token, etc.)
 *
 * Voce normalmente NAO precisa mexer neste arquivo ao criar uma
 * aplicacao nova -- só edita o .env.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Le o arquivo .env da raiz do projeto e injeta os valores em $_ENV.
// safeLoad() (em vez de load()) nao quebra o app se o .env nao existir.
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// --- Banco de dados ---
// Usado por App\Core\Database para montar a string de conexao PDO.
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'meu_app');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// --- Aplicacao ---
// APP_URL e usada em TODA a aplicacao para montar links absolutos,
// por exemplo: header('Location: ' . APP_URL . '/login')
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/lista-tarefas/public');
define('APP_KEY', $_ENV['APP_KEY'] ?? '');

// Em desenvolvimento, mostra os erros na tela (ajuda a debugar).
// Em producao, esconde os erros do usuario final (mais seguro).
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Configuracoes de seguranca do cookie de sessao:
// - httponly: JavaScript nao consegue ler o cookie (protege contra XSS)
// - use_only_cookies: nunca aceita sessao via ?PHPSESSID= na URL
// - samesite=Lax: reduz risco de CSRF vindo de outros sites
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

// Inicia a sessao (uma unica vez por requisicao).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
