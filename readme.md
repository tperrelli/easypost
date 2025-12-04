# Laravel + Inertia Project

This project is a Laravel 12 application using **Inertia.js** with Vue 3 on the frontend, running in a **Dockerized environment** with **PHP 8.3**, **NGINX**, and **MySQL 8**.

---

## Prerequisites

Make sure you have the following installed on your system:

* [Docker](https://www.docker.com/get-started)
* [Docker Compose](https://docs.docker.com/compose/install/)
* Optional: [Node.js & NPM](https://nodejs.org/) if you plan to run frontend scripts locally

---

## Docker Setup

The project comes with a `docker-compose.yml` that defines three services:

1. **app** – PHP 8.3 with Composer
2. **nginx** – NGINX web server
3. **mysql** – MySQL 8 database

Volumes and networks are already configured for persistent storage and inter-container communication.

---

## Steps to Build and Run the Project

### 1. Clone the repository

```bash
git clone <your-repo-url>
cd <your-project-folder>
```

### 2. Build Docker containers

```bash
docker-compose build
```

### 3. Start the containers

```bash
docker-compose up -d
```

This will start:

* `laravel-app` (PHP & Composer)
* `laravel-nginx` (web server)
* `laravel-mysql` (database)

---

### 4. Install PHP dependencies

Enter the PHP container:

```bash
docker exec -it laravel-app bash
```

Install Composer dependencies:

```bash
composer install
```

---

### 5. Install Node dependencies (frontend)

```bash
npm install
```

Optionally, build assets for production:

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev (in your host, not in the container)
```

---

### 6. Configure Environment

Copy the example `.env` file and set your variables:

```bash
cp .env.example .env
```

Update the `.env` file with database credentials matching the Docker MySQL service:

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

### 7. Run Migrations & Seeders

Inside the PHP container:

```bash
php artisan migrate --seed
```

---

### 8. Access the Application

Once the containers are running:

* Open your browser at [http://localhost](http://localhost)
* Laravel should be served via NGINX
* API endpoints and Inertia pages are accessible normally

### 9. Login with the following test user
* username test@example.com
* passwd: password

---

### 10. Stopping the Project

To stop all containers:

```bash
docker-compose down
```

To rebuild after changes:

```bash
docker-compose build --no-cache
docker-compose up -d
```

---

### 11. Useful Commands

* Enter PHP container: `docker exec -it laravel-app bash`
* Run artisan commands: `php artisan <command>`
* Run PHPUnit tests: `php artisan test` or `/vendor/bin/phpunit`
* Access MySQL container: `docker exec -it laravel-mysql mysql -u laravel -p`

---

### Notes

* All source code is mounted into the PHP container via volumes, so changes are reflected immediately.
* NGINX config is located at `./docker/nginx/default.conf`.
* MySQL data is persisted in the `mysql_data` Docker volume.
