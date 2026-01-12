

podman cp data/. php:/var/www/html/
podman exec php chown -R www-data:www-data /var/www
#!/usr/bin/env bash
set -euo pipefail

# 1) Pousser CI4 dans le conteneur
podman cp data/CI4 php:/var/www/html/

# 2) Permissions + init + vendor + migrations + seed
podman exec -it php sh -lc '
set -e
cd /var/www/html/CI4

if [ ! -d vendor ]; then
  composer install --no-interaction --prefer-dist
fi

# writable + permissions
mkdir -p writable/{cache,logs,session,uploads}
mkdir -p writable/uploads/{masters,previews}
touch writable/index.html
chown -R www-data:www-data writable
chmod -R 775 writable

# migrations + seed
php spark migrate --all
php spark db:seed DatabaseSeeder
'

echo "OK: code pushed + CI4 initialized (vendor/writable/migrate/seed)."
