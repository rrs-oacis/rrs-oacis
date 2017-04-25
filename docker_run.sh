#!/bin/sh

cd `dirname $0`

sudo docker run --rm -p 6040:6040 -v $(pwd)/src:/adf/src  -t -i test/test:1.0  /bin/bash