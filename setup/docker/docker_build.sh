#!/bin/sh

cd `dirname $0`

docker build ./ -t rrsoacis/rrsoacis:latest

