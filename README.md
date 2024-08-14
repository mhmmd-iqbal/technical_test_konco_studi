
## Konco Studio

## Requirement
- PHP 8.3
- MySQL
- redis

## Installation
- set .env file
- use below configuration on .env file for redis
```
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
```
- install package
```
composer install
```
- run migration
```
php artisan migrate
```
- run seeder
```
php artisan db:seed
```
- create APP key
```
php artisan key:generate
```
- run project
```
php artisan serve
php artisan queue:work
php artisan horizon
```
