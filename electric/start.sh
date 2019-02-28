#!/bin/bash

# first start. last start docker-compose up
# import database and start

docker-compose build
docker-compose up -d db
pg_restore -h 127.0.0.1 -U postgres -Fc -C -v --dbname=electric electric.backup
docker-compose stop
docker-compose up
