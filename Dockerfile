FROM php:8.2-apache

# Working directory set karo
COPY . /var/www/html/

# Leaderboard file create karo agar nahi hai
RUN touch /var/www/html/leaderboard.json

# Permissions set karo (Sabse important step)
RUN chmod 777 /var/www/html/leaderboard.json
RUN chown -R www-data:www-data /var/www/html/

# Apache port expose karo
EXPOSE 80
