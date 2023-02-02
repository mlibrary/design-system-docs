FROM drupal:9.5.2-apache

RUN apt-get update -yqq && apt-get install -yqq --no-install-recommends \
  default-mysql-client

RUN rm /opt/drupal/composer.*
COPY composer.json /opt/drupal/composer.json
COPY composer.lock /opt/drupal/composer.lock

RUN composer install

COPY sites/default/settings.php /var/www/html/sites/default/settings.php
COPY modules /var/www/html/modules
COPY themes /var/www/html/themes
COPY config /config-sync
