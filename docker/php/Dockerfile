FROM php:8.1-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip sqlite3 libsqlite3-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_sqlite \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN git config --global user.email "you@example.com" \ 
    && git config --global user.name "Your Name"

CMD bash -c "composer install && symfony serve --no-tls --dir=public"