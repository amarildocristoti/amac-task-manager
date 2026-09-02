<?php

namespace App\Core;

/**
 * ============================================================
 * SECURITY.PHP -- funcoes utilitarias de seguranca.
 * ============================================================
 *
 * QUANDO USAR CADA METODO:
 *
 * generateCsrfToken() / validateCsrfToken()
 *   -> Use em TODO formulario que muda dados (POST). Gere o token
 *      na hora de mostrar o formulario, valide na hora de processar
 *      o envio. Protege contra CSRF (um site malicioso forjando
 *      uma requisicao no seu nome).
 *
 * sanitize()
 *   -> Use ao EXIBIR na tela qualquer dado que veio do usuario
 *      (nome, comentario, titulo de tarefa, etc.), a menos que a
 *      view ja use htmlspecialchars() diretamente (o que da na mesma).
 *      Protege contra XSS.
 *
 * isValidEmail()
 *   -> Use ao VALIDAR um formulario que tem campo de email.
 */
class Security
{
    /**
     * Gera (ou reaproveita) um token aleatorio unico por sessao.
     * Coloque o retorno num <input type="hidden" name="csrf_token">.
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Confere se o token recebido do formulario bate com o da sessao.
     * hash_equals() evita "timing attacks" (mais seguro que ==).
     */
    public static function validateCsrfToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /** Escapa caracteres especiais de HTML antes de exibir na tela. */
    public static function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /** Confere se a string tem formato de email valido. */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
