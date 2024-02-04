#!/bin/sh

PHP_DIR=./php
ADDRESS="127.0.0.1:8000"

if [ ! -d $PHP_DIR ]; then
	echo $(pwd)
	echo "$PHP_DIR not found."
	exit 1
fi

sleep 0.1 && python3 -m webbrowser -t "http://$ADDRESS" &
exec bwrap --dev-bind $PHP_DIR / --bind ./src /app --chdir /app php -S $ADDRESS
