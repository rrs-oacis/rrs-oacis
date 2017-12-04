#!/bin/sh

cd `dirname $0`
cd ../../

cp ./setup/docker/supplement/server.sh ./
chmod a+x ./server.sh

docker rm -f rrsoacis_sandbox
docker create --name rrsoacis_sandbox -p 3080:3080 -p 3000:3000 -p 49138:49138/udp -v `pwd`:/home/oacis/rrs-oacis -t rrsoacis/rrsoacis

echo RRS-OACIS sandbox is created as rrsoacis_sandbox.
echo You can start the sandbox using following command.
echo "# docker start rrsoacis_sandbox"
echo
