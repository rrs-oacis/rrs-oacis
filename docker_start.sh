#!/bin/sh

cd `dirname $0`

docker rm -f rrsoacis >/dev/null 2>&1
docker create --rm -p 3080:3080 -p 127.0.0.1:3000:3000 -v $(pwd)/src:/home/oacis/rrs-oacis/src -v $(pwd)/public:/home/oacis/rrs-oacis/public -v $(pwd)/ruby:/home/oacis/rrs-oacis/ruby -v $(pwd)/agents:/home/oacis/rrs-oacis/rrsenv/AGENT --name rrsoacis -i rrsoacis/rrsoacis:latest
docker start rrsoacis
