#!/bin/bash

# Set your AWS credentials (make sure you have the necessary AWS CLI installed and configured)
AWS_REGION="eu-west-3"
AWS_ACCOUNT_ID="057133510824"

# Replace these values with your specific ones
APP_NAME="hamsterauto"
APP_PATH="/opt/dev_custom/projects/$APP_NAME/"

cd $APP_PATH

# Add ssh key to authentication handler
ssh-add

git stash
git checkout main
git pull origin main

GIT_COMMIT=$(git rev-parse --short HEAD)

# Authenticate Docker with AWS ECR
aws ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com

# Check if the repository exists; if not, create it
aws ecr describe-repositories --repository-names $APP_NAME || aws ecr create-repository --repository-name $APP_NAME

# Build your Docker image
docker build --no-cache -t $APP_NAME:$GIT_COMMIT .
docker tag $APP_NAME:$GIT_COMMIT $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$APP_NAME:$GIT_COMMIT
docker tag $APP_NAME:$GIT_COMMIT $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$APP_NAME:latest

# Push the image to AWS ECR
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$APP_NAME:$GIT_COMMIT
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$APP_NAME:latest