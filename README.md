### Instruções pra subir pela primeira vez

coloque sua chave do Asaas no .env dentro da pasta ´laravel´

agora roda ´docker compose up´

quando terminar e tudo tiver OK rode `docker compose exec -it web ash` e dentro do container rode `php artisan migrate`

agora ao acessar `localhost` deve aparecer a aplicação rodando normalmente

para os testes e coverage: `docker compose exec -it web ash` e dentro do container rode `php artisan test --coverage` e deve resultar em 90% de cobertura de testes
