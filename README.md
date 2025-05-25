### Instruções pra subir pela primeira vez

crie um .env na raiz do projeto com base no .env.example para definir algumas configurações do mysql

agora crie um .ev dentro da pasta ´laravel´ com base no .env.example, coloque as configurações de banco

agora roda ´docker compose up´

quando terminar e tudo tiver OK rode `docker compose exec -it web ash` e dentro do container rode `composer install` e `php artisan key:generate` e `php artisan migrate --seed`

agora ao acessar `localhost` deve aparecer a aplicação rodando normalmente
