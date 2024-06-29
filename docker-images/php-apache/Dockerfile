FROM php:apache
ENV TZ=Asia/Tel_Aviv

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    docker-php-ext-install pdo pdo_mysql



RUN apt-get update && \
    apt-get -y install tzdata cron unzip p7zip-full libzip-dev && \
    docker-php-ext-install zip

RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone && \
    printf '[Date]\ndate.timezone="%s"\n', $TZ > /usr/local/etc/php/conf.d/tzone.ini && \
    echo "* * * * * /usr/local/bin/php /var/www/html/scheduler.php >> /var/log/cron.log 2>&1\n" >> /etc/crontab

COPY . /var/www/html/

RUN chmod 0755 /etc/crontab
RUN crontab /etc/crontab
RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground