#!/bin/bash

# Check if the database file exists, and create it if it doesn't
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating database file..."
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

# Execute the original CMD
exec "$@"
