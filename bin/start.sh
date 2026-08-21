#!/bin/sh

docker-compose up -d --build
echo "Containers started. App: http://localhost:8080  phpMyAdmin: http://localhost:8081"
