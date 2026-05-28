# Book Catalog

Yii2 + MySQL web application for managing books, authors, guest subscriptions, and author statistics.

## Features

* Book catalog with CRUD
* Author catalog with CRUD
* Many-to-many relation between books and authors
* Book cover image upload
* Guest access for viewing books and authors
* Guest subscription to new books by selected author
* Authenticated user access for creating, updating, and deleting books/authors
* Top 10 authors report by selected release year
* Seed command with demo books, authors, subscriptions, and cover images

## Requirements

* Docker
* Docker Compose

## Setup

Copy environment file:

```bash
cp .env.example .env
```

Start containers:

```bash
docker compose up -d
```

Install dependencies:

```bash
docker compose exec php composer install
```

Run migrations:

```bash
docker compose exec php php yii migrate
```

Create demo data:

```bash
docker compose exec php php yii seed
```

Or reset demo data:

```bash
docker compose exec php php yii seed/reset
```

The reset command clears books, authors, book-author relations, and subscriptions, then recreates demo data.

## Login

Default admin user:

```text
Username: admin
Password: admin
```

## Main URLs

Home:

```text
http://localhost:8000
```

Books:

```text
http://localhost:8000/index.php?r=book/index
```

Authors:

```text
http://localhost:8000/index.php?r=author/index
```

Top Authors Report:

```text
http://localhost:8000/index.php?r=report/top-authors
```

Example report by year:

```text
http://localhost:8000/index.php?r=report/top-authors&year=1993
```

## Database

Database connection inside Docker:

```text
Host: mysql
Port: 3306
Database: yii2
User: yii2
Password: yii2
```

Database connection from host machine:

```text
Host: 127.0.0.1
Port: 3307
Database: yii2
User: yii2
Password: yii2
```

## Seed Images

Seed source images are stored in:

```text
web/seed-images
```

When running the seed command, images are copied to:

```text
web/uploads/books
```

Book records store public image paths like:

```text
/uploads/books/war-and-peace.png
```

## Access Rules

Guest users can:

* View books
* View authors
* Subscribe to new books by selected author
* View the top authors report

Authenticated users can:

* View books and authors
* Create books and authors
* Update books and authors
* Delete books and authors

## Useful Commands

Run migrations:

```bash
docker compose exec php php yii migrate
```

Check migration history:

```bash
docker compose exec php php yii migrate/history
```

Run seed:

```bash
docker compose exec php php yii seed
```

Reset seed data:

```bash
docker compose exec php php yii seed/reset
```

Open PHP container shell:

```bash
docker compose exec php bash
```

## Submission Notes

Do not include generated or runtime directories in the submitted archive:

```text
vendor/
runtime/
web/assets/
```

Include project source files, migrations, seed command, views, controllers, models, config examples, and seed images.
