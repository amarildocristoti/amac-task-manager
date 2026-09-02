<?php

namespace App\Core\Traits;

/**
 * ============================================================
 * RENDERSVIEW.PHP -- capacidade de "desenhar uma tela".
 * ============================================================
 *
 * Isto e um TRAIT (nao uma classe normal) -- um bloco de codigo
 * reutilizavel que outras classes "importam" com "use RendersView;",
 * sem precisar de heranca.
 *
 * QUANDO USAR: qualquer classe que precise chamar $this->view(...)
 * deve declarar "use RendersView;" logo no topo do corpo da classe.
 * Ja vem incluido automaticamente em App\Core\Controller -- entao
 * todo Controller ja ganha isso de graca. So precisaria adicionar
 * manualmente se criar, por exemplo, um Middleware que tambem
 * precise mostrar uma tela (ex: uma pagina de erro 403 estilizada).
 */
trait RendersView
{
    /**
     * @param string $view Caminho dentro de src/Views/, sem ".php".
     *                      Ex: 'tasks/index' -> src/Views/tasks/index.php
     * @param array  $data Vira variaveis dentro da view. Ex:
     *                      ['tasks' => $lista] cria $tasks na view.
     */
    protected function view(string $view, array $data = []): void
    {
        // Transforma cada chave do array numa variavel PHP de verdade.
        extract($data);

        $viewPath = __DIR__ . '/../../Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("View não encontrada: {$view}");
        }

        // Toda tela e "montada" em 3 pedaços: cabecalho comum,
        // o conteudo especifico da pagina, e rodape comum.
        require __DIR__ . '/../../Views/layout/header.php';
        require $viewPath;
        require __DIR__ . '/../../Views/layout/footer.php';
    }
}
