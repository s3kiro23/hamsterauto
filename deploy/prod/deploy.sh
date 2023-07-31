#!/bin/bash

AWS_REGION="eu-west-3"
AWS_ACCOUNT_ID="057133510824"

# Replace these values with your specific ones
APP_NAME="hamsterauto"
APP_PATH="/opt/dev_custom/projects/$APP_NAME/"

cd $APP_PATH
cd deploy/prod

git stash
git checkout main
git pull origin main

docker pull $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$APP_NAME:latest

docker-compose down -v
docker-compose up -d

VOLUME_DIR=`docker volume inspect --format '{{ .Mountpoint }}' $APP_NAME'_app'`
TARGET_DIR='/var/www/'$APP_NAME

echo "Symlink : $VOLUME_DIR to $TARGET_DIR"

if [ -d $VOLUME_DIR ]; then
    if [[ -L $TARGET_DIR ]]; then
        rm $TARGET_DIR
    fi
    ln -s $VOLUME_DIR $TARGET_DIR
fi