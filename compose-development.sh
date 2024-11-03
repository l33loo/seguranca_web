#!/bin/bash

set -e

touch app/logs/err.log && touch app/logs/login.log

cd app && composer install && cd ..

docker compose up --build
