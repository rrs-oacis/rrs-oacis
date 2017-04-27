#!/bin/sh

cd `dirname $0`

apt-get update -y
apt-get -y install curl
apt -y install php7.0-cli
apt-get -y install php-mbstring
apt-get -y install php7.0-zip