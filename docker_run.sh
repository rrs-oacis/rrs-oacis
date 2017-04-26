#!/bin/sh

cd `dirname $0`

sudo docker run --rm -p 6040:6040 -v $(pwd)/src:/adf/src -v $(pwd)/public:/adf/public -v $(pwd)/ruby:/adf/ruby -t -i test/test:1.0  /bin/bash