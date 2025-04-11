# Desafio Técnico - Desenvolvedor FullStack
Este sistema fullStack foi desenvolvido para gerenciamento de estoque, com autenticação segura e uma interface moderna e responsiva. O projeto é dividido entre backend (API REST) e frontend (interface web).

Tecnologias utilizadas:

# Back-End
PHP 8.1 com MySQL.
Laravel.
Composer.

# Front-End
Vue.js 3.5.

# Demais tecnologias
Nginx.
Docker.

## ⚙️ Funcionalidades
Cadastros, visualização, edição e remoção de Produtos, Categorias e Usuários;
Login e Logout com credenciais.

## Como inicializar o projeto
Execute o seguinte comando como solicitado no desafio:
```bash
  docker-compose up -d
```

Em seguida, execute:
```bash
  docker exec -it laravel-app php artisan migrate --seed
```
O comando acima é utilizado para gerar dados aleatórios no sistema e um usuário inicial

## Acessando via browser
Acesse: http://localhost:8080 para utilizar o sistema
Dados de acesso inicial:
E-Mail: teste@teste.com
Senha: 123
