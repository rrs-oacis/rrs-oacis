#!/bin/sh

cd `dirname $0`

docker rm -f rrsoacis 2>&1 >/dev/null
docker create --rm -p 6040:6040 -p 127.0.0.1:3000:3000 -v $(pwd)/src:/home/oacis/adf/src -v $(pwd)/public:/home/oacis/adf/public -v $(pwd)/ruby:/home/oacis/adf/ruby -v $(pwd)/agents:/home/oacis/adf/rrsenv/AGENT --name rrsoacis -i rrsoacis/rrsoacis:latest
docker start rrsoacis
