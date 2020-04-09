# Voucher PLUS

Easy Installation
-----------------
**Requirements**
- docker
- docker-compose

**Installation Step**
- run server: `$ docker-compose up -d`
- run: `$ docker exec db bash`
- run: `:/# mysql -u root -p` 
- use the *MYSQL_ROOT_PASSWORD* in the `docker-compose.yml` file
- run: `mysql> GRANT ALL PRIVILEGES ON $MYSQL_DATABASE.* TO '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';`
- run: `mysql> FLUSH PRIVILEGES;`
- run: `mysql> EXIT;`
- run: `:/# exit`
- run: `$ docker-compose exec app composer install`
- run: `$ docker-compose exec app cp .env.example .env`
- run: `$ docker-compose exec app php artisan key:generate`
- go to `localhost:8080`

## Regular use

- to shut down: `$ docker-compose down --remove-orphans`
- for restarting: `$ docker-compose up -d`
