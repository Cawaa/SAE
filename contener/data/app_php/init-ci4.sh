#!/bin/sh
set -eu

cd /var/www/html/CI4

# 1) Dépendances PHP (composer)
if [ ! -d vendor ]; then
  echo "[init] composer install..."
  composer install --no-interaction --no-progress
fi

# 2) Dossiers writable
echo "[init] ensure writable folders..."
mkdir -p public/uploads/previews
mkdir -p writable/uploads/masters

# 3) Droits (Apache tourne en www-data)
chown -R www-data:www-data public/uploads writable/uploads
chmod -R 775 public/uploads writable/uploads

# 4) Migrations / seeds (idempotent)
echo "[init] migrate..."
php spark migrate --all

# Seed seulement si tu veux systématiquement (sinon: garde manuel)
if [ "${SEED_DB:-0}" = "1" ]; then
  echo "[init] seed..."
  php spark db:seed DatabaseSeeder
fi

echo "[init] done."
