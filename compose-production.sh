#!/bin/bash

set -e

OS=$(uname)

# Load production MySQL secrets from `pass` into RAM.
if [[ "$OS" == "Linux" ]]; then
    ./tls/generate-tls.sh
    
    DIR="/mnt/seguranca_web/secrets/"

    if [ ! -d "$DIR" ]; then
        sudo mkdir -p "$DIR"
    fi

    if mountpoint -q "$DIR"; then
        echo "tmpfs is already mounted at ${DIR}."
    else
        if sudo mount -t tmpfs -o size=500m --onlyonce tmpfs "$DIR"; then
            echo "Mounted tmpfs at ${DIR}."
            sudo chmod 0600 "$DIR"
        else
            echo "Failed to mount tmpfs at ${DIR}." >&2
            exit 1
        fi
    fi

    FILE="${DIR}prod.env"
    if pass uac/seguranca_web/prod.env > "$FILE"; then
        chmod 0400 "$FILE"
        echo "MySQL production secrets have been loaded into ${FILE}."
    else
        echo "Failed to load secrets into ${FILE}." >&2
        exit 1
    fi
else
    echo "This machine is running an unsupported operating system: ${OS}." >&2
    exit 1
fi

docker compose up