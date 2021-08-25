FROM php:7.4-cli

ENV TIMEZONE Europe/Moscow

RUN pecl install redis-5.1.1 \
    && docker-php-ext-enable redis \
    && docker-php-ext-install mysqli pdo pdo_mysql  \
    && docker-php-ext-enable pdo_mysql

COPY . /opt/app/
WORKDIR /opt/app/
CMD bash /opt/app/runner.sh