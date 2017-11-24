#!/bin/sh

cd `dirname $0`

#waiting for mongod boot
until [ "$(mongo --eval 'printjson(db.serverStatus().ok)' | tail -1 | tr -d '\r')" == "1" ]
do
  sleep 1
  echo "waiting for mongod boot..."
done
mongo --eval 'db.adminCommand( { setParameter: 1, failIndexKeyTooLong: false } )'

if [ -e /etc/rrsoacis-init.sh -a ! -e /home/oacis/rrs-oacis/data/.rrs-oacis.initialized ] ; then
    chmod a+x /etc/rrsoacis-init.sh
    su oacis -c /etc/rrsoacis-init.sh
fi
touch /home/oacis/rrs-oacis/data/.rrs-oacis.initialized

/etc/init.d/php7.0-fpm restart
/etc/init.d/nginx restart

/home/oacis/oacis_start.sh

