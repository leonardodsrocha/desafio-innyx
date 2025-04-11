# innyx

Teste de desenvolvimento em Laravel com Vue

Para testar o conteúdo desenvolvido, será necessário o Docker, Docker Compose e Git(ou pode baixar direto)

Usando o git execute o comando git clone https://github.com/jprogramador/innyx

Acesse o diretório principal innyx

execute docker-compose up -d

Ao terminar execute docker exec -it laravel-app php artisan migrate --seed
O comando acima é utilizado para gerar dados aleatórios no sistema e um usuário inicial

Acesse http://localhost:8080 para utilizar o sistema
Dados de acesso inicial:
E-Mail: teste@teste.com
Senha: 123

Para parar a aplicação execute docker-compose down

Para reiniciar execute novamente docker-compose up -d
Não é necessário executar o migrate novamente, somente na primeira vez
