FROM drupal:9-apache

RUN apt-get update -yqq && apt-get install -yqq --no-install-recommends \
  default-mysql-client

RUN composer require drush/drush
RUN composer require drupal/pathauto
RUN composer require drupal/codesnippet
RUN composer require drupal/ckeditor_details

COPY config /drupal-config
