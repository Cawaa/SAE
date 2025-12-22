#!/bin/bash
# 1. Copie des fichiers vers le conteneur
podman cp data/. php:/var/www/html/

# 2. Droits de lecture pour Apache sur tout le projet
podman exec php chmod -R 755 /var/www/html/CI4

# 3. Droits d'écriture pour le dossier writable (indispensable pour le cache et les logs)
podman exec php chmod -R 777 /var/www/html/CI4/writable