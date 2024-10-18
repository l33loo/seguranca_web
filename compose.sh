#!/bin/bash

set -e

OS=$(uname)

# Load production MySQL secrets into RAM.
if [[ "$OS" == "Linux" ]]; then
    DIR="/mnt/seguranca_web/secrets/"

    if [ ! -d "$DIR" ]; then
        mkdir -p "$DIR"
    fi

    if mount -t tmpfs -o size=500m --onlyonce tmpfs "$DIR"; then
        echo "Mounted tmpfs at ${DIR}."
    else
        echo "Failed to mount tmpfs at ${DIR}." >&2
        exit 1
    fi

    FILE="${DIR}mysql.prod.env"
    if pass uac/seguranca_web/mysql.env > "$FILE"; then
        chmod 0600 "$FILE"
        echo "MySQL production secrets have been loaded into ${FILE}."
    else
        echo "Failed to load secrets into ${FILE}." >&2
        exit 1
    fi
else
    echo "This machine is running an unsupported operating system: ${OS}." >&2
    exit 1
fi