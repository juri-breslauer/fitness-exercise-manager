# Fitness Exercise Manager

Fitness Exercise Manager is a Laravel-powered application for managing a structured fitness exercise catalog with media support, an admin panel, and an API-first architecture.

The project is intended as a portfolio-grade backend system that demonstrates clean application structure, dataset import workflows, media handling, and REST API development in a real-world Laravel environment.

## Features

- Exercise catalog with categories and detailed exercise data
- Media support for images and GIF demonstrations
- Admin panel for managing exercises, categories, and related content
- REST API designed as the primary integration layer
- JSON dataset import via an Artisan command
- Service and Repository based application structure

## Tech Stack

- PHP 8.x
- Laravel
- MySQL or PostgreSQL
- Redis, optional
- Docker

## Installation

```bash
git clone git@github.com:juri-breslauer/fitness-exercise-manager.git
cd fitness-exercise-manager

cp .env.example .env

docker-compose up -d

php artisan key:generate
php artisan migrate --seed
```

## Import Exercises

Import exercises from a JSON dataset:

```bash
php artisan exercises:import
```

## API Endpoints

| Method | Endpoint              | Description              |
| ------ | --------------------- | ------------------------ |
| GET    | `/api/exercises`      | List exercises           |
| GET    | `/api/exercises/{id}` | Show exercise details    |
| GET    | `/api/categories`     | List exercise categories |

## Project Structure

- Services: business logic
- Repositories: data access layer
- DTOs: data transfer objects
- Console Commands: dataset import and maintenance tasks
- API Resources: consistent JSON responses


## Author

Juri Breslauer
