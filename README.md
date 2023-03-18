<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Create Database
- use docker-compose ( please check port :3306 not used & database surplus not exists )
```
docker-compose up -d
```
- use db server exists ( Running sql command )
```
CREATE DATABASE `surplus`;
```

## Set Environment
if password database not 'root' please update on DB_PASSWOR)
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:wynsk/smnfW+0kOe87oH2W7rKLJtQkEnmtSX70hdGHE=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=surplus
DB_USERNAME=root
DB_PASSWORD=root

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

## Run migration
```
php artisan migrate
```

## Run API
```
php artisan serve
```

## API Contract
1. Category

* Create
    * URL : /api/v1/category
    * Method : POST
    * cURL :
    ```
    curl --location --request POST 'http://localhost:8000/api/v1/category' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name":""
    }'
    ```
* Get List
    * URL : /api/v1/category
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/category'
    ```
* Get By ID
    * URL : /api/v1/category/{id}
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/category/1'
    ```
* Update
    * URL : /api/v1/category/{id}
    * Method : PUT
    * cURL :
    ```
    curl --location --request PUT 'http://localhost:8000/api/v1/category/1' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name":""
    }'
    ```
* Delete
    * URL : /api/v1/category/{id}
    * Method : DELETE
    * cURL :
    ```
    curl --location --request DELETE 'http://localhost:8000/api/v1/category/1'
    ```

2. Image

* Create
    * URL : /api/v1/image
    * Method : POST
    * cURL :
    ```
    curl --location --request POST 'http://localhost:8000/api/v1/image' \
    --form 'name=""' \
    --form 'file=@"'
    ```
* Get List
    * URL : /api/v1/image
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/image'
    ```
* Get By ID
    * URL : /api/v1/image/{id}
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/image/1'
    ```
* Update
    * URL : /api/v1/image/{id}
    * Method : POST
    * cURL :
    ```
    curl --location --request POST 'http://localhost:8000/api/v1/image/1' \
    --form 'name=""' \
    --form 'file=@"'
    ```
* Delete
    * URL : /api/v1/image/{id}
    * Method : DELETE
    * cURL :
    ```
    curl --location --request DELETE 'http://localhost:8000/api/v1/image/1'
    ```

3. Product

* Create
    * URL : /api/v1/product
    * Method : POST
    * cURL :
    ```
    curl --location --request POST 'http://localhost:8000/api/v1/product' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name":"",
        "description":"",
        "category_id":0,
        "image_id":0
    }'
    ```
* Get List
    * URL : /api/v1/product
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/product'
    ```
* Get By ID
    * URL : /api/v1/product/{id}
    * Method : GET
    * cURL :
    ```
    curl --location --request GET 'http://localhost:8000/api/v1/product/1'
    ```
* Update
    * URL : /api/v1/product/{id}
    * Method : PUT
    * cURL :
    ```
    curl --location --request PUT 'http://localhost:8000/api/v1/product/1' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "name":"0",
        "description":"",
        "category_id":0,
        "image_id":0
    }'
    ```
* Delete
    * URL : /api/v1/product/{id}
    * Method : DELETE
    * cURL :
    ```
    curl --location --request DELETE 'http://localhost:8000/api/v1/product/1'
    ```
