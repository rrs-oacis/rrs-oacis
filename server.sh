#!/bin/sh

cd `dirname $0`

echo 'Start the server'
php -S 0.0.0.0:6040 -t public -c php.ini public/_app.php