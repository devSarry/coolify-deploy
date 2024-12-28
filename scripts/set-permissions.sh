#!/bin/bash

# Database Volume and Storage Volume paths input variables
DB_VOLUME_PATH=$1
STORAGE_VOLUME_PATH=$2

if [ -z $DB_VOLUME_PATH ] || [ -z $STORAGE_VOLUME_PATH ]; then
  echo "Please provide the database volume and storage volume paths"
  exit 1
fi

# if directory $STORAGE_VOLUME_PATH/framework/{sessions,views,cache} does not exist create
if [ ! -d $STORAGE_VOLUME_PATH/framework/sessions ]; then
  mkdir -p $STORAGE_VOLUME_PATH/framework/{sessions,views,cache}
  chown www-data:www-data $STORAGE_VOLUME_PATH/framework
  chmod 755 $STORAGE_VOLUME_PATH/framework
fi

# if directory $DB_VOLUME_PATH does not exist create sqlite database file
if [ ! -f $DB_VOLUME_PATH/database.sqlite ]; then
  echo "Creating database file..."
  touch $DB_VOLUME_PATH/database.sqlite
  chown www-data:www-data $DB_VOLUME_PATH/database.sqlite
fi
