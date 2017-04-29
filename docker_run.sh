#!/bin/sh

cd `dirname $0`

sudo docker run --rm -p 6040:6040 -p 127.0.0.1:3000:3000 -v $(pwd)/src:/home/oacis/adf/src -v $(pwd)/public:/home/oacis/adf/public -v $(pwd)/ruby:/home/oacis/adf/ruby -t -i test/test:1.0