Online cource project
==========

Deploying local sandbox:
==========

* ```docker-compose build```
* ```docker-compose up -d```
* ```docker-compose exec --user=www-data php mkdir -p var/jwt/```
* ```docker-compose exec --user=www-data php openssl genrsa -out var/jwt/private.pem -passout pass:REPLACE_WITH_YOUR_PASS -aes256 4096```
* ```docker-compose exec --user=www-data php openssl rsa -pubout -in var/jwt/private.pem -passin pass:REPLACE_WITH_YOUR_PASS -out var/jwt/public.pem```
* ```docker-compose exec --user=www-data php bin/console d:m:m```
* Optional, if you need some dummy initial data: ```docker-compose exec --user=www-data php bin/console doctrine:fixtures:load --no-interaction --append```
## Entrypoints
* API doc: [/doc](http://localhost/doc)

Useful commands and shortcuts
==========

### Shortcuts
It is recommended to add short aliases for the following frequently used container commands:

* `docker-compose exec --user=www-data php bash` to enter in php container
* `docker-compose exec --user=www-data php composer` to run composer
* `docker-compose exec --user=www-data php bin/console` to run Symfony CLI commands
* `docker-compose exec db psql` to run PostgreSQL commands


### Checking code style and running tests
Fix code style by running PHP CS Fixer:
```bash
docker-compose exec php vendor/bin/php-cs-fixer fix
```

Code style
==========
* do not use php docs that duplicate type hints
* mandatory `:void` use
