FROM php:8.4-apache

EXPOSE 80

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

CMD ["apache2-foreground"]
