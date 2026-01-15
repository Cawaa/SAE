#!/bin/bash

# Configuration des identifiants
EMAIL="elricdufresne@gmail.com"
MDP="nU600103!"

echo "--- 1. Authentification préalable ---"
# On se connecte ici avec les bons paramètres pour éviter le blocage interactif
echo "$MDP" | podman login docker.io -u "$EMAIL" --password-stdin

echo "--- 2. Lancement via create.sh ---"
# Maintenant que la session est active, create.sh passera l'étape du login avec succès
./scripts/create.sh

echo "--- 3. Transfert via push.sh ---"
./scripts/push.sh

echo "--- 4. Attente du démarrage complet de MySQL (30 secondes) ---"
# 5 secondes ne suffisent pas pour l'initialisation de MySQL 8 au premier lancement
sleep 30

echo "--- 5. Configuration interne de CI4 ---"
podman exec php /bin/bash -c "
    cd CI4 && \
    composer install && \
    mkdir -p writable/cache writable/debugbar writable/logs writable/session writable/uploads && \
    mkdir -p writable/uploads/masters && \
    mkdir -p public/uploads/previews && \
    chmod -R 777 writable && \
    chmod -R 777 public/uploads && \
    chown -R www-data:www-data writable public/uploads && \
    mkdir -p public/images/avatars && \
    chmod -R 777 public/images/avatars && \
    if [ -f env ]; then mv env .env; fi && \
    php spark migrate && \
    php spark db:seed DatabaseSeeder
"

echo "--- Procédure terminée ! ---"