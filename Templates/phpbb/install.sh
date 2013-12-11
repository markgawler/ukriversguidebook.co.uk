#!/bin/bash

if [ -z "$1" ]
then
    echo "Local install"
    sudo rsync -av prosilver /home/mrfg/ukrgb/phpbb/styles/
elif [ "$1" == "area51" ]
then
    echo "Install on Area51"
    rsync -av prosilver  --rsh=ssh root@server30282.uk2net.com:/public_html/sites/area51/phpbb/styles/
elif [ "$1" == "www" ]
then
    echo "Install on www"
    rsync -av prosilver  --rsh=ssh root@server30282.uk2net.com:/public_html/sites/ukrgb/phpbb/styles/
elif [ "$1" == "j3dev" ]
then
    echo "Install on J3dev"
    SSH="ssh -i /home/mrfg/.ssh/J3Dev1.pem"
    rsync -av -e "$SSH" prosilver ubuntu@j3dev.wler.co.uk:/space/http/j3dev/phpbb/styles/
else
     echo "NO INSTALL"
fi
