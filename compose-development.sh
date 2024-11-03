#!/bin/bash

set -e

cd app && composer install && cd ..

docker compose up --build
