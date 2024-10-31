FROM php:8.3-apache

# Set the Apache Document Root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update the default apache config files to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache mod_rewrite and ssl module
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2enmod headers
RUN a2ensite default-ssl

# Custom config files to enable TLS
COPY ./tls/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./tls/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN docker-php-ext-install pdo_mysql