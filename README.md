
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### === Setup  ====


### 1. Change config php.int file
```terminal
​
max_input_time=6000
max_execution_time=1200
upload_max_filesize=1G
memory_limit=1G
​​
 ```
### 2. Command install 
```terminal
chmod -R 777 storage/
​
composer install
​
```

### 3. Make environment configuration  
```terminal
cp .env.example .env
​
​
```
### 4. Configuration database connection in .env file
```terminal
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=my_db_name
DB_USERNAME=my_db_user
DB_PASSWORD=my_password
```
​
### 5. Migrate database and seeder
​
```terminal
php artisan key:generate
​
php artisan jwt:secret
​
php artisan l5-swagger:generate 
​
php artisan reload:cache
​
php artisan migrate
​
php artisan db:seed
```
### 6. Change config my.ini file
```terminal
​
max_allowed_packet=10M
```
### 7. Run test
#### 7.1 Set up .env.testing (sure .env exactly data)
```terminal
​
cp .env .env.testing
```
#### 7.2 Change DB information in .env.testing
```terminal
​
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=:memory:
DB_USERNAME=root
DB_PASSWORD=
```
#### 7.3 Migration and seed in db test
```terminal
# Run new migrate and seed
​php artisan migrate --seed --env=testing

# Clear DB and run again
​php artisan migrate:fresh --seed --env=testing
```
#### 7.4 Run test (Maybe need run php artisan config:clear - if have notification)
```terminal
# Run one file
​php artisan test {path)
(Example: php artisan test tests/Feature/Controller/AuthTest.php)

# Run all file test
​php artisan test
```
