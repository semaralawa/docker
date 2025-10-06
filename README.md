# Laravel Docker Development Environment

A Docker-based development environment for Laravel applications with PHP, Nginx, MySQL, and Redis.

## Prerequisites

- Docker
- Docker Compose
- Make (optional, but recommended for easier command execution)

## Getting Started

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. **Copy environment files**
   ```bash
   cp .env.docker.example .env.docker
   cp .env.example .env
   ```

3. **Update environment variables**
   Edit `.env.docker` and `.env` files with your configuration.

## Available Commands

Use `make` commands for common tasks:

| Command | Description |
|---------|-------------|
| `make build` | Build Docker images |
| `make up` | Start all containers |
| `make down` | Stop and remove containers |
| `make rebuild` | Rebuild images and restart containers |
| `make shell` | Open bash shell in app container |
| `make composer args="..."` | Run Composer commands (e.g., `make composer args="install"`) |
| `make artisan args="..."` | Run Artisan commands (e.g., `make artisan args="migrate"`) |
| `make migrate` | Run database migrations |
| `make migrate-fresh` | Fresh database migration with seeding |
| `make logs service=app` | View container logs (default: all) |
| `make db-shell` | Open MySQL client |
| `make test` | Run PHPUnit tests |
| `make restart` | Restart all containers |

## Services

- **App**: PHP-FPM with Laravel (port: 9000)
- **Web**: Nginx web server (port: 8080)
- **DB**: MySQL 8.0 (port: 3306)
- **Redis**: Redis cache (port: 6379)

## PHP Version

Specify PHP version when starting services:
```bash
make up PHP_VER=8.1
```

## Project Structure

- `docker/` - Docker configuration files
  - `nginx/` - Nginx configuration
  - `php-fpm/` - PHP-FPM configuration
- `.env.docker` - Environment variables for Docker
- `docker-compose.dev.yml` - Docker Compose configuration

## Accessing the Application

- Web interface: http://localhost:8080
- MySQL host: `db` (from containers) or `localhost:3309` (from host)
- Redis host: `redis` (from containers) or `localhost:6379` (from host)

## Development Workflow

1. Start the environment:
   ```bash
   make up
   ```

2. Install dependencies:
   ```bash
   make composer args="install"
   ```

3. Generate application key:
   ```bash
   make artisan args="key:generate"
   ```

4. Run migrations:
   ```bash
   make migrate
   ```

5. Access the application at http://localhost:8080

## License

[Specify your license here]
