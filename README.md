# Base Project

## Installation

### System Requirements

-   PHP: 8.2.7
-   Node: 16.14.0
-   NPM: 9.8.1
-   Mysql: 8.0.26

### Configuration

Clone this repository

Copy and make the required configuration changes in the .env file: `cp .env.example .env`

Install dependencies with Composer

> For development: `composer install`
>
> For production: `composer install --optimize-autoloader --no-dev`

Install dependencies with NPM

> For production: `npm install`

Run the database migrations

> `php artisan migrate`

For production, please see more at [Laravel Deployment Guide](https://laravel.com/docs/10.x/deployment)

# Run with Docker

## Install dependencies (vendor folder) and Export uid and gid

```sh
cp -f .docker/dev/.dev.env .env
chmod u+x .docker/composer-package-install.sh
chmod u+x .docker/set-info.sh
.docker/composer-package-install.sh
source .docker/set-info.sh
sudo chown -R $(id -u):$(id -g) .
```

## Build containers

```docker
docker compose -f docker-compose.dev.yml build
```

## Run all services

**You must stop MySQL service in your local machine**
```sh
systemctl stop mysql
```

```
docker compose -f docker-compose.dev.yml up -d
```

## Migrate database

```docker
docker compose -f docker-compose.dev.yml exec api php artisan migrate
```

-   Now, you can call API
-   Sample endpoint: http://127.0.0.1:4000/api/seminars
-   If you don't like port 4000, change **APP_PORT** in **.env** file

## Stop all services

```
docker compose -f docker-compose.dev.yml down

```

## Note

### MySQL Service (Only for dev environment)

-   In your local machine, connect to the MySQL database using info below
    -   host: 127.0.0.1
    -   port: 3306
    -   username: mockuser
    -   password: mockpass
