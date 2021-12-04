[![SymfonyInsight](https://insight.symfony.com/projects/a0327649-afab-4d12-8ae5-a0c9e660d381/big.svg)](https://insight.symfony.com/projects/a0327649-afab-4d12-8ae5-a0c9e660d381)

# BileMo Catalog API

API crafting for BilMo customers/partners.

## Test coverage report
url

## Environment and technologies
* Symfony 5.3.12
* Composer 2.1.12
* PHP 8.0.13
* MySQL 8.0.21

## Installation
Execute the following command to clone the project:
```
git clone https://github.com/alkomaJunior/bilemo-catalog-api.git
```
Use the following command to install dependencies:
```
composer install
```
## Database
Set up your database url in .env file:
```
DATABASE_URL=mysql://database_user:database_password@127.0.0.1:3306/bilemo-api?version=your_database_version
```
Create the database with the following command:
```
php bin/console doctrine:database:create
```
Create the schema with the following command:
```
php bin/console doctrine:schema:create
```
Generate migrations with the following command:
```
php bin/console make:migration
```

Apply migrations with the following command:
```
php bin/console doctrine:migrations:migrate
```
## Run the application
run the server with:
```
php bin/console server:run
```
## Documentation API - Swagger
```
https://localhost:8000 (local server)
https://bilemo-catalog-api.herokuapp.com/api/doc (production server)
```
