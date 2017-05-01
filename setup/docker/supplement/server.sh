#!/bin/sh

cd `dirname $0`

if [ rrsoacis-init.sh -a ! -e .rrs-oacis.initialized ] ; then
    chmod a+x rrsoacis-init.sh
    ./rrsoacis-init.sh
fi
touch .rrs-oacis.initialized

/home/oacis/oacis_start.sh &
php -S 0.0.0.0:3080 -t public -c php.ini public/_app.php