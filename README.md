# Exemplo: Lista de Tarefas

Aplicação completa e funcional construída em cima do mini-framework,
demonstrando o padrão Model → Controller → View com uma única entidade
("tasks"), sem autenticação.

## Instalação

```bash
composer install
cp .env.example .env
# edite o .env com as credenciais do seu MySQL
mysql -u root -p < database.sql
php -S localhost:8000 -t public
```

Acesse `http://localhost:8000`.

## Arquivos-chave (todos comentados)

```
database.sql                          -> tabela "tasks"
src/Models/Task.php                   -> queries (all, create, toggleDone, delete)
src/Controllers/TaskController.php    -> index, store, toggle, delete
src/Views/tasks/index.php             -> formulário + lista
public/index.php                      -> as 4 rotas da aplicação
```

Use este exemplo como referência ao criar sua própria entidade —
o padrão é sempre o mesmo, só mudam os nomes e os campos.
