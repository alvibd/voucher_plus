# Voucher PLUS

Easy Installation
-----------------
**Requirements**
- docker
- docker-compose

**Installation Step**
- clone the repository: `$ git clone https://github.com/alvibd/voucher_plus.git`
- go to project directory: `$ cd voucher_plus`
- checkout to dev brance: `$ git checkout -b dev`
- run server: `$ docker-compose up -d`
- run: `$ docker exec db bash`
- run: `:/# mysql -u root -p` 
- use the *MYSQL_ROOT_PASSWORD* in the `docker-compose.yml` file
- run: `mysql> GRANT ALL PRIVILEGES ON voucher_app.* TO 'app_user'@'%' IDENTIFIED BY '9l+-Upr@br4';`
- run: `mysql> FLUSH PRIVILEGES;`
- run: `mysql> EXIT;`
- run: `:/# exit`
- run: `$ docker-compose exec app composer install`
- run: `$ docker-compose exec app cp .env.example .env`
- run: `$ docker-compose exec app php artisan key:generate`
- run migration: `$ docker-compose exec app php artisan migrate --seed`
- compile js,css files: `$ docker-compose exec app npm install && npm run dev`
- go to `localhost:8080`

## Regular use

- to shut down: `$ docker-compose down --remove-orphans`
- for restarting: `$ docker-compose up -d`

## for running unit test

- get into app bash: `$ docker-compose exec app bash`
- change the config and cache to test environment: `$ php artisan config:cache --env=testing`
- run test using: `$ vendor/bin/phpunit` or `$ php artisan test`
- in order to use development database change config,cache: `$ php artisan config:cache`

## for compiling frontend assets

- get into app bash: `$ docker-compose exec app bash`
- run npm command: `$ npm run dev`
