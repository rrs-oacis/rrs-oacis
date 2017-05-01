#!/bin/sh

cd `dirname $0`

docker rm -f rrsoacis >/dev/null 2>&1
docker create --rm -p 3080:3080 -p 3000:3000 --name rrsoacis -i rrsoacis/rrsoacis:latest
docker start rrsoacis
