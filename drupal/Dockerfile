FROM drupal:9.5.2-apache

RUN apt-get update -yqq && apt-get install -yqq --no-install-recommends \
  default-mysql-client

COPY composer.json /opt/drupal/composer.json
COPY composer.lock /opt/drupal/composer.lock

RUN composer install
