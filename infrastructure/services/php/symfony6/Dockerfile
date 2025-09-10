FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT /public

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list \
    && apt update && apt install -y symfony-cli

COPY ./site.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/conf.d/99-custom.ini

RUN apt install -y cron

RUN apt install -y supervisor && apt install -y nano && apt install -y htop

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath  && docker-php-ext-enable pdo_mysql

RUN apt-get update && apt-get install -y libzip-dev && apt-get install -y zlib1g-dev && apt-get install -y libpng-dev && apt-get install -y libicu-dev

RUN docker-php-ext-install zip 

RUN docker-php-ext-install gd

RUN docker-php-ext-install intl

# Install additional aplications
RUN apt install tzdata

# Configure timezone
ENV TZ=Europe/Madrid

RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && date

# Give execution rights on the supervisor
#RUN chmod 0644 /etc/supervisor/conf.d
#COPY ./messenger-worker-dev.ini /etc/supervisor/conf.d/messenger-worker.conf


# Copy hello-cron file to the cron.d directory
COPY ./cronjobs/cronjob /etc/cron.d/cronjob

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/cronjob

# Give execution rights on the supervisor
# RUN chmod 0644 /etc/supervisor/conf.d
# COPY ./config/supervisord.d/vps-consumer.conf /etc/supervisor/conf.d/async-functions.conf

# Apply cron job
RUN crontab /etc/cron.d/cronjob

COPY ./start.sh /usr/local/bin/start.sh
RUN chmod 0777 /usr/local/bin/start.sh

ENTRYPOINT ["sh", "-c"]
CMD ["sh /usr/local/bin/start.sh"]

