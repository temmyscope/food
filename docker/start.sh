#!/bin/sh

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-local}

if [ "$role" = "app" ]; then

  echo "Running the app..."
  exec php artisan octane:start --workers=auto --task-workers=auto --server=swoole --host=0.0.0.0 --port=8080

else
  echo "Could not match the container role \"$role\""
  exit 1
fi