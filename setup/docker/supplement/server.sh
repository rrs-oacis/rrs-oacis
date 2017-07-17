#!/bin/sh

cd `dirname $0`

if [ -e /etc/rrsoacis-init.sh -a ! -e /home/oacis/rrs-oacis/data/.rrs-oacis.initialized ] ; then
    chmod a+x /etc/rrsoacis-init.sh
    su oacis -c /etc/rrsoacis-init.sh
fi
touch /home/oacis/rrs-oacis/data/.rrs-oacis.initialized

/etc/init.d/php7.0-fpm restart
/etc/init.d/nginx restart

/home/oacis/oacis_start.sh

