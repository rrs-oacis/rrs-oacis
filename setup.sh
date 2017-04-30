#!/bin/bash

cd `dirname $0`

printf 'install composer'
curl -S https://getcomposer.org/installer | php

printf 'setup php for rrs-oacis'
php composer.phar install
