FROM php:8.2-fpm

COPY --from=composer/composer:latest-bin /composer /usr/local/bin/composer

RUN apt-get update --fix-missing && \
    apt-get install -y \
    git unzip \
    nodejs npm \
    libicu-dev \
    libpq-dev \
    libc-client-dev \
    libssh-dev \
    libkrb5-dev && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && docker-php-ext-install bcmath \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl\
    && docker-php-ext-install pdo_pgsql pgsql

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]