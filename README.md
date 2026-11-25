# BibliotecaORM
Projeto de estudo de Programação II — João Antonio de Souza.

Implementação didática independente, elaborada com auxílio de IA a partir do enunciado e do tutorial do professor. Revise e adapte antes de entregar.

## Executar no Linux
1. Instale PHP 8.2 ou superior, extensão pdo_mysql e MySQL.
2. Execute `mysql -u root -p < sql/schema.sql` ou abra o SQL no MySQL Workbench.
3. Configure as credenciais de um usuário local com acesso ao banco:
   `export DB_USER=seu_usuario DB_NAME=biblioteca_orm DB_HOST=127.0.0.1`
   `read -rs DB_PASS; export DB_PASS`
4. Execute `php tests/entidades.php` e `php tests/demonstracao.php`.
5. Registre as saídas reais no relatório. Use um banco de testes, pois a demonstração insere e exclui registros.

## Como funciona
Entidades guardam dados e validam valores. A classe Entidade controla a identidade. RepositoryInterface define o contrato; AbstractRepository compartilha o CRUD e a execução parametrizada. Os repositórios concretos fornecem tabelas, colunas e hidratação. LivroRepository recupera os autores com JOIN e mantém os vínculos em uma transação. A exclusão com vínculos é bloqueada pela FK; após desvincular, pode ser realizada.

## Para estudar e adaptar
Explique por que PDO sozinho não é ORM, como o método hidratar transforma uma linha em objeto, por que o contrato permite polimorfismo e o que acontece se um vínculo falhar. Experimente alterar uma validação e criar um teste próprio. A implementação usa carregamento separado de autores (N+1 em listagens), sem ORM comercial ou identity map global. As identidades são estáveis, mas objetos de consultas diferentes não são necessariamente a mesma instância.

## Validação
Sintaxe PHP e teste de entidades verificados. A demonstração MySQL precisa ser executada localmente. Não há credenciais reais no projeto.
