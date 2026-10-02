# Docker Setup for Laravel Backend

This document provides instructions for running the Laravel backend with Docker.

## Prerequisites

- Docker Desktop installed on your machine
- Docker Compose (included with Docker Desktop)

## Quick Start

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Build and start the containers:
   ```bash
   docker-compose up -d --build
   ```

3. Install composer dependencies:
   ```bash
   docker-compose exec app composer install
   ```

4. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. Run migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. Install Passport and run passport:install:
   ```bash
   docker-compose exec app php artisan passport:install
   ```

7. Run seeders:
   ```bash
   docker-compose exec app php artisan db:seed
   ```

8. Access the application at `http://localhost:8000`

## Services

The docker-compose.yml includes the following services:

- **app**: PHP-FPM container running Laravel
- **nginx**: Nginx web server serving the application
- **mysql**: MySQL 8.0 database
- **redis**: Redis cache server

## Common Commands

### Start containers
```bash
docker-compose up -d
```

### Stop containers
```bash
docker-compose down
```

### View logs
```bash
docker-compose logs -f
```

### Run artisan commands
```bash
docker-compose exec app php artisan <command>
```

### Install npm packages
```bash
docker-compose exec app npm install
```

### Run npm build
```bash
docker-compose exec app npm run build
```

### Clear Laravel cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

## Database Configuration

The `.env` file should have the following database configuration:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

## Troubleshooting

### Container won't start
- Check if port 8000 is already in use
- Run `docker-compose logs` to see error messages

### Database connection issues
- Ensure MySQL container is running: `docker-compose ps`
- Check database credentials in `.env` file
- Try restarting the containers: `docker-compose restart`

### Permission issues
- Run `docker-compose exec app chmod -R 755 storage`
- Run `docker-compose exec app chmod -R 755 bootstrap/cache`

## Stopping and Cleaning

To stop all containers and remove volumes:
```bash
docker-compose down -v
```

This will stop and remove all containers, networks, and volumes.
