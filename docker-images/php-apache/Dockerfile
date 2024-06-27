FROM php:apache
ENV TZ=Asia/Tel_Aviv

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/