#!/bin/sh

cd `dirname $0`

docker run --rm -p 3080:3080 -p 127.0.0.1:3000:3000 -v $(pwd)/src:/home/oacis/adf/src -v $(pwd)/public:/home/oacis/adf/public -v $(pwd)/ruby:/home/oacis/adf/ruby -v $(pwd)/agents:/home/oacis/adf/rrsenv/AGENT -t -i rrsoacis/rrsoacis:latest

