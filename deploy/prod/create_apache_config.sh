#!/bin/bash

echo "########## APACHE CONFIG CREATOR ##########"

read -p 'App name: ' APP_NAME
while [[ -z $APP_NAME ]]
do
    echo "Please enter an app name !"
    read -p 'App name: ' APP_NAME
done

read -p 'Domain name: ' DOMAIN_NAME
while [[ -z $DOMAIN_NAME ]]
do
    echo "Please enter an Domain name !"
    read -p 'Domain name: ' DOMAIN_NAME
done

#$CONF_PATH="/etc/apache2/site-availables/"
GENERIC_CONF="generic"
GENERIC_DOM="generic.com"
HTTPS_REDIRECT="    Redirect permanent / https://$DOMAIN_NAME"

#cd $CONF_PATH

cp $GENERIC_CONF.conf $APP_NAME.conf

sed -i '' -e "s#$GENERIC_DOM#$DOMAIN_NAME#g" "$APP_NAME.conf"
sed -i '' -e "s#$GENERIC_CONF#$APP_NAME#g" "$APP_NAME.conf"

# Enable website
a2ensite $APP_NAME.conf
systemctl reload apache2

# Activate https on domain
certbot --apache -d $DOMAIN_NAME

sed -i '' -e '5 a\
'"$HTTPS_REDIRECT"'' $APP_NAME.conf

systemctl reload apache2


