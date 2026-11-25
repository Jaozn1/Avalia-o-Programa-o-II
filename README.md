# BibliotecaORM

Projeto desenvolvido para a disciplina de Programação II do curso de Ciência da Computação da UNOESC.

## Sobre o projeto

O sistema tem como objetivo simular o gerenciamento de livros e autores de uma biblioteca, utilizando os conceitos de orientação a objetos e ORM para trabalhar com o banco de dados.

## Tecnologias utilizadas

- PHP 8.3
- MySQL 8.0
- PDO
- Visual Studio Code
- Linux Mint
- Git e GitHub

## Funcionalidades

- Cadastro de livros e autores.
- Busca e listagem de registros.
- Atualização e exclusão de dados.
- Relacionamento entre livros e autores.
- Controle de transações e rollback.
- Validação das entidades.

## Como executar

Primeiro, importe o arquivo `sql/schema.sql` no MySQL:

```bash
mysql -u root -p < sql/schema.sql
```

Configure a conexão com o banco de dados:

```bash
export DB_USER=root
export DB_NAME=biblioteca_joao
read -rs DB_PASS
export DB_PASS
```

Depois, execute os testes pelo terminal:

```bash
php tests/entidades.php
php tests/demonstracao.php
```

Utilize um banco de testes, pois a demonstração insere e exclui registros.

## Resultados

Os testes foram realizados no Linux Mint utilizando PHP e MySQL. As operações de cadastro, busca, atualização, relacionamento, rollback e exclusão apresentaram os resultados esperados.

## Observações

O projeto foi elaborado com base no material disponibilizado pelo professor, com auxílio de IA na implementação e documentação. A configuração do ambiente e a execução dos testes foram realizadas localmente.

## Acadêmico

João Antonio de Souza

Ciência da Computação – UNOESC

Programação II