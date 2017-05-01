#!/bin/sh

cd `dirname $0`

docker run --rm -p 3080:3080 -p 3000:3000 -t -i rrsoacis/rrsoacis:latest

