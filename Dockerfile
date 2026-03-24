# PHP 8.2 with Apache server use kar rahe hain
FROM php:8.2-apache

# Sari files ko container ke web root mein copy karo
COPY . /var/www/html/

# Leaderboard file ko initialize karo
RUN touch /var/www/html/leaderboard.json

# Sabse important: Apache user (www-data) ko file likhne ki permission do
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 775 /var/www/html/ && \
    chmod 666 /var/www/html/leaderboard.json

# Apache default port 80 use karta hai
EXPOSE 80
