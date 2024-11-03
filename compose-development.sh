#!/bin/bash

set -e

touch app/logs/err.log

cd app && composer install && cd ..

docker compose up --build
