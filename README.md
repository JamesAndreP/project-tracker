# Project Tracker

A full-stack project management application with Laravel backend and Vue 3 frontend.

## Prerequisites

- **Backend**: Docker and Docker Compose
- **Frontend**: Node.js version > 22

## Project Structure

```
koda/
├── backend/          # Laravel API backend
└── frontend/         # Vue 3 frontend application
```

## Backend Installation (Docker)

Navigate to the backend directory:

```bash
cd backend
```

### Setup Steps

1. Copy environment file:
```bash
cp .env.example .env
```

2. Build and start containers:
```bash
docker-compose up -d --build
```

3. Install dependencies:
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

6. Generate Passport personal token:
```bash
docker-compose exec app php artisan passport:client --personal
```

7. Run seeders:
```bash
docker-compose exec app php artisan db:seed
```

8. Generate Passport encryption keys:
```bash
docker compose exec app php artisan passport:keys --force
```

9. Set proper permissions for OAuth keys:
```bash
docker compose exec app chown www-data:www-data storage/oauth-private.key storage/oauth-public.key
docker compose exec app chmod 660 storage/oauth-private.key storage/oauth-public.key
```

10. Clear cached files:
```bash
docker compose exec app php artisan optimize:clear
```

The backend will be available at `http://localhost:8000`

### Docker Services

- **app**: PHP-FPM container running Laravel
- **nginx**: Nginx web server
- **mysql**: MySQL 8.0 database

### Common Docker Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Run artisan commands
docker-compose exec app php artisan <command>
```

## Frontend Installation

Navigate to the frontend directory:

```bash
cd frontend
```

### Setup Steps

1. Copy environment file:
```bash
cp .env.example .env
```

2. Install dependencies:
```bash
npm install
```

3. Start development server:
```bash
npm run dev
```

The frontend will be available at `http://localhost:5173` (or another port if 5173 is in use)

### Build for Production

```bash
npm run build
```

## Default Credentials

After running the seeders, you can log in with:

- **Email**: admin@example.com
- **Password**: admin123

## Features

- **Authentication**: Laravel Passport-based API authentication
- **Project Management**: Full CRUD operations for projects
- **Pagination**: Infinite scroll pagination for projects list
- **Search**: Search functionality across projects
- **Soft Delete**: Projects are soft deleted instead of permanent deletion
- **Responsive Design**: Blue and white themed UI

## Architecture

### Backend
- **Framework**: Laravel 12
- **Authentication**: Laravel Passport
- **Database**: MySQL
- **Architecture Pattern**: Controller → Service → Repository (with interfaces)

### Frontend
- **Framework**: Vue 3 with TypeScript
- **State Management**: Pinia
- **Routing**: Vue Router
- **Build Tool**: Vite
- **HTTP Client**: Axios

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user (requires auth)
- `GET /api/user` - Get authenticated user (requires auth)

### Projects
- `GET /api/projects` - Get all projects (requires auth)
- `GET /api/projects-paginated` - Get paginated projects (requires auth)
- `GET /api/stats` - Get project stats ~ Total Count, In Progress, and Completed (requires auth)
- `POST /api/projects` - Create project (requires auth)
- `GET /api/projects/{id}` - Get single project (requires auth)
- `PUT /api/projects/{id}` - Update project (requires auth)
- `DELETE /api/projects/{id}` - Delete project (requires auth)

## Troubleshooting

### Backend

**Docker container won't start:**
- Check if port 8000 is already in use
- Run `docker-compose logs` to see error messages

**Database connection issues:**
- Ensure MySQL container is running: `docker-compose ps`
- Check database credentials in `.env` file

**OAuth key permissions error:**
- Run the permission commands in the setup steps
- Ensure keys are owned by www-data user

### Frontend

**Port 5173 is in use:**
- Vite will automatically use the next available port (e.g., 5174)
- Check the terminal output for the actual URL

**API connection errors:**
- Ensure backend Docker containers are running
- Check that `VITE_API_URL` in `.env` points to the correct backend URL
- Verify CORS configuration in backend

## License

This project is open source and available under the MIT License.
