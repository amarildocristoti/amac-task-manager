<?php

namespace App\Core;

/**
 * ============================================================
 * ROUTER.PHP -- decide qual Controller/metodo trata cada URL.
 * ============================================================
 *
 * QUANDO USAR: voce nao chama nada deste arquivo diretamente no
 * dia a dia -- ele e usado dentro do public/index.php assim:
 *
 *   $router = new Router();
 *   $router->get('/tasks', 'TaskController@index');
 *   $router->post('/tasks/store', 'TaskController@store');
 *   $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
 *
 * Voce normalmente NAO precisa editar este arquivo -- so usa os
 * metodos ->get() e ->post() no index.php para cadastrar rotas novas.
 */
class Router
{
    /** Todas as rotas cadastradas: $routes['GET']['/tasks'] = [...] */
    private array $routes = [];

    /** Cadastra uma rota GET (usada para "ver" uma pagina). */
    public function get(string $uri, string $action, array $middlewares = []): void
    {
        $this->addRoute('GET', $uri, $action, $middlewares);
    }

    /** Cadastra uma rota POST (usada para "enviar dados"/alterar algo). */
    public function post(string $uri, string $action, array $middlewares = []): void
    {
        $this->addRoute('POST', $uri, $action, $middlewares);
    }

    private function addRoute(string $method, string $uri, string $action, array $middlewares): void
    {
        $this->routes[$method][$uri] = [
            'action'      => $action,      // Ex: 'TaskController@index'
            'middlewares' => $middlewares, // Ex: [AuthMiddleware::class] -- veja Core/Middleware/
        ];
    }

    /**
     * Chamado UMA VEZ, no final do public/index.php, depois de
     * cadastradas todas as rotas.
     */
    public function dispatch(string $uri, string $method): void
    {
        // Extrai so o caminho da URL, descartando ?query=strings.
        $uri = parse_url($uri, PHP_URL_PATH);

        // --- Calculo automatico do "caminho base" ---
        // Se o projeto roda em http://localhost/meu-app/public/,
        // as rotas foram cadastradas como '/tasks' (sem o prefixo).
        // Aqui descobrimos e removemos esse prefixo automaticamente,
        // para funcionar tanto em subpasta quanto na raiz de um dominio.
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        if ($basePath !== '' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        } else {
            // Fallback: cobre o caso de acessar SEM "/public" na URL
            // (quando o .htaccess da raiz redireciona por baixo dos panos).
            $projectRoot = rtrim(dirname($basePath), '/');
            if ($projectRoot !== '' && $projectRoot !== '.' && str_starts_with($uri, $projectRoot)) {
                $uri = substr($uri, strlen($projectRoot));
            }
        }

        $uri = rtrim($uri, '/') ?: '/';

        // Procura, entre as rotas do metodo HTTP atual (GET/POST),
        // uma cujo padrao bata com a URL pedida.
        foreach ($this->routes[$method] ?? [] as $route => $config) {
            // Suporte a parametros dinamicos: '/tasks/edit/{id}' vira
            // uma regex que aceita letras/numeros/hifen no lugar de {id}.
            $pattern = preg_replace('#\{[a-zA-Z]+\}#', '([a-zA-Z0-9_-]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove o "match completo", sobra so os parametros

                // Executa os middlewares da rota ANTES do Controller
                // (ex: bloquear quem nao esta logado).
                foreach ($config['middlewares'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle();
                }

                // 'TaskController@index' -> classe App\Controllers\TaskController, metodo index()
                [$controllerName, $methodName] = explode('@', $config['action']);
                $controllerClass = "App\\Controllers\\{$controllerName}";

                $controller = new $controllerClass();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        // Nenhuma rota bateu -> pagina nao existe.
        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
    }
}
