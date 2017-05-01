#!/bin/sh

cd `dirname $0`

docker rm -f rrsoacis >/dev/null 2>&1
docker create --rm --name rrsoacis -i rrsoacis/rrsoacis:latest
docker start rrsoacis
