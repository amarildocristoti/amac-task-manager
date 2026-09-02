<?php

namespace App\Core;

use App\Core\Traits\RendersView;

/**
 * ============================================================
 * CONTROLLER.PHP -- classe base de TODO Controller da aplicacao.
 * ============================================================
 *
 * QUANDO USAR: toda vez que voce criar um Controller novo (ex:
 * TaskController, ProductController, ContactController...), ele
 * deve "extends Controller" para ganhar de graca os 3 metodos
 * abaixo, sem precisar reescrever em cada um.
 *
 * Exemplo minimo de um Controller usando esta base:
 *
 *   class TaskController extends Controller
 *   {
 *       public function index(): void
 *       {
 *           $this->view('tasks/index', ['tasks' => [...]]);
 *       }
 *   }
 */
abstract class Controller
{
    use RendersView; // Traz o metodo view() -- veja Traits/RendersView.php

    /**
     * Redireciona o navegador para outra pagina do proprio site
     * e para a execucao do script.
     * Ex: $this->redirect('/tasks');
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . $path);
        exit; // Sempre necessario apos um redirect.
    }

    /**
     * Le um campo enviado por formulario (POST) ou pela URL (GET),
     * ja removendo espacos extras. Use em vez de mexer direto em
     * $_POST/$_GET.
     * Ex: $titulo = $this->input('title');
     *     $pagina = (int) $this->input('page', 1); // com valor padrao
     */
    protected function input(string $key, $default = null)
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }
}
