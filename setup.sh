#!/bin/bash

cd `dirname $0`

export TERM="xterm-color" 


echo '##################################'
echo '             RRS-ADF    '
echo '##################################'
echo '        Powered by PHP  '
echo '        by: Kamiya  '
echo '##################################'

printf '\e[35m:downloadComposer\e[m\n'
curl -S https://getcomposer.org/installer | php
echo '🚒  Finish !'
printf '\e[34m==> downloadComposer\e[m\n'

printf '\e[35m:setupComposer\e[m\n'
php composer.phar install
echo '🚒  Finish !'
printf '\e[34m==> setupComposer\e[m\n'