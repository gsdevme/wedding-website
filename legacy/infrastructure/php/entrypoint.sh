#!/bin/sh
set -e

echo "Container running as $(id)"

if [ "${APP_ENV}" = "prod" ]; then
    if [ "$EUID" = "0" ]
    then
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"
      echo "Warning, container is running is root, this is not advised"

      # In the case we are executing as "root" lets ensure the symfony command
      # is executed as www-data though to ensure the file permissions are correct
      su www-data -s /bin/sh -c 'php /app/bin/console cache:warm --env=prod --no-debug'
    else
      bin/console cache:warm --env=prod --no-debug
    fi
fi

exec "$@"
