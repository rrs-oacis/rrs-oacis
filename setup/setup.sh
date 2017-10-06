#!/bin/bash

cd `dirname $0`
cd ..

echo 'install composer'
curl -S https://getcomposer.org/installer | php

echo 'setup php for rrs-oacis'
php composer.phar install
