FROM php:8.3-apache

# Set the Apache Document Root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update the default apache config file to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql