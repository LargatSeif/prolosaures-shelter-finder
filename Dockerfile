FROM php:8.2-fpm-alpine

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    curl \
    && docker-php-ext-install pdo_mysql mysqli pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN composer install --no-ansi --no-interaction --no-scripts --optimize-autoloader

RUN apk del .build-deps

# Copy existing application source code
COPY . /var/www

# Change ownership of the /var/www directory
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
