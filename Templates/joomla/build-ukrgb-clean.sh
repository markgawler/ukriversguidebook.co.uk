#!/bin/bash

lessc ukrgb-clean/less/template.less ukrgb-clean/css/template.css
echo "Creating Zip..."
zip -r ukrgb-clean ukrgb-clean/*


SSH="ssh -i /home/mrfg/.ssh/J3Dev1.pem"

echo "RInstallling via rsync..."
#rsync -av -e "$SSH" ukrgb-clean/* ubuntu@j3dev.wler.co.uk:/space/http/j3dev/joomla/templates/ukrgbclean/

rsync -av -e "$SSH" ukrgb-clean/* ubuntu@j3dev.wler.co.uk:/space/http/j3dev/joomla/templates/ukrgbclean/


echo "Done."
