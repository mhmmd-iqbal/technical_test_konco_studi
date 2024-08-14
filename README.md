
# Konco Studio

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
- create passport key
```
php artisan passport:client --password
```
- copy client on .env
```
PASSPORT_PASSWORD_CLIENT_ID=
PASSPORT_PASSWORD_SECRET=
```
- run project
```
php artisan serve
php artisan horizon
php artisan queue:work
```
- to open horizon, run command
```
{localhost:8000}/horizon
```


## API Reference
#### Register 
```
POST /api/register
```
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | **Required**. |
| `email` | `string` | **Required**. |
| `password` | `string` | **Required**. |

#### Login 
```
POST /api/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **Required**. |
| `password` | `string` | **Required**. |


#### Create Transaction
```
POST /api/transaction
```
- Header: Bearer {token}

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `amount` | `int` | **Required**. |

#### Update status payment
```
PUT /api/transaction/{:transactionID}
```
- Header: Bearer {token}

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `status` | `string` | **Required**. value is 'completed' or 'failed'|


#### Get List Transactions
```
GET /api/transaction
```
- Header: Bearer {token}

#### Get Transaction Summary
```
GET /api/transaction/summary
```
- Header: Bearer {token}

### DUMMY ACCOUNT
all dummy account password is: "password"
