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
