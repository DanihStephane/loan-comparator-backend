#!/bin/bash

# Ensure MySQL service is running
service mysql start

# Wait for database connection
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  echo "Waiting for database connection..."
  sleep 2
done

# Drop database schema if exists
php bin/console doctrine:database:drop --force --if-exists

# Create database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Load fixtures
php bin/console doctrine:fixtures:load --no-interaction
