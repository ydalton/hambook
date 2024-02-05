#!/bin/sh

PHP_DIR=./php
ADDRESS="127.0.0.1:8000"

set -e 

if [ ! -d $PHP_DIR ]; then
	echo $(pwd)
	echo "$PHP_DIR not found."
	exit 1
fi

if [ "$1" = "sh" ]; then
	CMD=ash
else
	CMD="php -S $ADDRESS"
	sleep 0.1 && python3 -m webbrowser -t "http://$ADDRESS" &
fi

exec bwrap --dev-bind $PHP_DIR / --bind ./src /app --chdir /app $CMD
