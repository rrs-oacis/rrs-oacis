#!/bin/sh

cd `dirname $0`

docker build ./ -t test/test:1.0