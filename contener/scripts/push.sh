# Copie le dossier data local vers le serveur web du conteneur
podman cp data/. php:/var/www/html/
podman exec php chown -R www-data:www-data /var/www