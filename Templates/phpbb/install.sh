#!/bin/bash

if [ -z "$1" ]
then
    echo "Local install"
    sudo rsync -av prosilver /home/mrfg/ukrgb/phpbb/styles/
elif [ "$1" == "area51" ]
then
    echo "Install on Area51"
    rsync -a prosilver  --rsh=ssh root@server30282.uk2net.com:/public_html/sites/area51/phpbb/styles/
elif [ "$1" == "www" ]
then
    echo "Install on www"
    rsync -a prosilver  --rsh=ssh root@server30282.uk2net.com:/public_html/sites/ukrgb/phpbb/styles/
else
     echo "NO INSTALL"
fi
