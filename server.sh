#!/bin/sh

cd `dirname $0`

if [ /etc/rrsoacis-init.sh -a ! -e /home/oacis/rrs-oacis/data/.rrs-oacis.initialized ] ; then
    chmod a+x /etc/rrsoacis-init.sh
    /etc/rrsoacis-init.sh
fi
touch /home/oacis/rrs-oacis/data/.rrs-oacis.initialized

/home/oacis/oacis_start.sh &
php -S 0.0.0.0:3080 -t public -c php.ini public/_app.php

