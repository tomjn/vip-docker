FROM php:fpm

# Install memcached for PHP 7
RUN apt-get update && apt-get install -y \
      curl \
      libjpeg-dev \
      libmemcached-dev \
      libpng12-dev \
      libpq-dev \
      git \
      subversion \
      mariadb-client \
    && rm -rf /var/lib/apt/lists/* 

RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-configure \
      gd \
      --with-png-dir=/usr \
      --with-jpeg-dir=/usr \
    && docker-php-ext-install \
      gd \
      memcached \
      mysqli \
      opcache \
    && rm /tmp/memcached.tar.gz

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY .docker/php.ini /usr/local/etc/php/conf.d/wordpress.ini

COPY .docker/wp-config.php /var/www/html/
WORKDIR /var/www/html/wordpress/

# Install wp-cli
RUN curl -o /usr/local/bin/wp -SL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli-nightly.phar \
	&& chmod +x /usr/local/bin/wp

COPY .docker/install-multisite /usr/local/bin/install-multisite
CMD /usr/local/bin/install-multisite
