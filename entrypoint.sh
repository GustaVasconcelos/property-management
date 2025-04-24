#!/bin/sh

echo "Aguardando o banco de dados ficar disponível..."

until nc -z db 3306; do
  sleep 1
done

echo "Banco disponível. Rodando migrações..."
php /var/www/html/app/Migrations/runMigrations.php

echo "Iniciando servidor PHP..."
php -S 0.0.0.0:8000 -t public
