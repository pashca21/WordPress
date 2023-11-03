FROM wordpress:latest

RUN apt update &&\
    apt install -y wget zip &&\
    pecl install xdebug-2.9.5 &&\
    docker-php-ext-enable xdebug && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini &&\
    wget -O /tmp/cookie-law-info.zip 'https://downloads.wordpress.org/plugin/cookie-law-info.zip' &&\
    unzip -o /tmp/cookie-law-info.zip -d /usr/src/wordpress/wp-content/plugins/ &&\
    wget -O /tmp/palmeria.zip 'https://downloads.wordpress.org/theme/palmeria.zip' &&\
    unzip -o /tmp/palmeria.zip -d  /usr/src/wordpress/wp-content/themes/ &&\
    echo " include_once (ABSPATH . 'wp-content/sample_data/demo_loader.php');" >> /usr/src/wordpress/wp-content/themes/palmeria/functions.php

