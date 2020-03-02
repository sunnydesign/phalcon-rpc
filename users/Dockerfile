FROM debian:9

RUN apt-get update

RUN apt-get install -y \ 
    apt-transport-https \
    lsb-release \
    ca-certificates \
    wget \ 
    curl \
    net-tools \
    openssl \
    mc \ 
    htop \
    supervisor \
    composer \
    libpcre3-dev \
    sqlite3

RUN apt-get install -y nginx

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
RUN apt-get update && apt-get install -y \
    php7.2 \
    php7.2-cli \
    php7.2-fpm \
    php7.2-curl \
    php7.2-mbstring \
    php7.2-gettext \
    php7.2-fileinfo \
    php7.2-json \
    php7.2-psr \
    php7.2-mysql \
    php7.2-sqlite3 \
    php7.2-sqlite

RUN curl -s "https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh" | bash
RUN apt-get install -y --allow-unauthenticated php7.2-phalcon

RUN mkdir -p /var/run/php

COPY ./fs/ /

RUN chown -R www-data:www-data /var/www/users \
    && cd /var/www/users \
    && composer install

RUN /var/www/users/migration.php

RUN ln -sf /dev/stderr /var/log/nginx/users-error.log

#RUN vendor/bin/phalcon-migrations migration run

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]