#!/bin/bash

set -e

OS=$(uname)

# Load production secrets from `pass` into RAM.
if [[ "$OS" == "Linux" ]]; then
    DIR="/mnt/seguranca_web/secrets/"

    if [ ! -d "$DIR" ]; then
        sudo mkdir -p "$DIR"
    fi

    if mountpoint -q "$DIR"; then
        echo "tmpfs is already mounted at ${DIR}."
    else
        if sudo mount -t tmpfs -o size=500m --onlyonce tmpfs "$DIR"; then
            echo "Mounted tmpfs at ${DIR}."
        else
            echo "Failed to mount tmpfs at ${DIR}." >&2
            exit 1
        fi
    fi

    sudo chmod -R 0777 $DIR

    # Load MySQL secrets
    MYSQL_FILE="${DIR}mysql.prod.env"
    if pass uac/seguranca_web/mysql.prod.env > $MYSQL_FILE; then
        # chmod 0400 "$MYSQL_FILE"
        echo "MySQL production secrets have been loaded into ${MYSQL_FILE}."
    else
        echo "Failed to load MySQL secrets into ${MYSQL_FILE}." >&2
        exit 1
    fi

    # Load App secrets
    APP_FILE="${DIR}app.prod.env"
    if pass uac/seguranca_web/app.prod.env > "$APP_FILE"; then
        # chmod 0400 "$APP_FILE"
        echo "App production secrets have been loaded into ${APP_FILE}."
    else
        echo "Failed to load App secrets into ${APP_FILE}." >&2
        exit 1
    fi
else
    echo "This machine is running an unsupported operating system: ${OS}." >&2
    exit 1
fi

touch app/logs/err.log && touch app/logs/login.log

cd app && composer install && cd ..

docker compose up --build
