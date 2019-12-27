## Requirements
1.Php 7.2 or higher

## Set up this project
1. Clone repository
2. Run `composer install`
3. Run `php artisan key:generate`
4. Set up `.env` file and set db name in .env
5. Run `php artisan wild:card`
6. Project will run in this link: http://127.0.0.1:8080/
7. admin username:admin@gmail.com ,password:123456

## API Url List
1. login[POST] - http://127.0.0.1:8080/api/login
2. all[GET] - http://127.0.0.1:8080/api/values
3. one or more[GET] - http://127.0.0.1:8080/api/values?tasks=1,2
4. insert[POST] - http://127.0.0.1:8080/api/values
5. update[PUT] - http://127.0.0.1:8080/api/values/1
