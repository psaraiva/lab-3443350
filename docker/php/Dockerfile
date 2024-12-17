FROM php:7.4-cli

USER 0

RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libsqlite3-dev \
    sqlite3

RUN docker-php-ext-install pdo pdo_sqlite

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./src /var/www/

# Ajustado para 1002 devido usuário local, caso necessário, alterar para 1000 (thx WSL)
RUN adduser -u 1002 --disabled-login --no-create-home --disabled-password admin
RUN chown -R admin:admin /var/www/
RUN chmod 655 /var/www/

USER admin
WORKDIR /var/www/
