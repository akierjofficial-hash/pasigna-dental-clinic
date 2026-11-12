FROM node:22-alpine AS frontend

WORKDIR /build
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js .
RUN npm run build

FROM richarvey/nginx-php-fpm:3.1.6

# Put Laravel exactly where this image expects it
WORKDIR /var/www/html
COPY . /var/www/html

# Dependencies and Vite output are intentionally excluded from Git/Docker context.
# Build them into the deploy image instead of relying on the local machine.
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
COPY --from=frontend /build/public/build /var/www/html/public/build
RUN chmod +x /var/www/html/scripts/*.sh

# Use your Laravel nginx config (this is the one that matters)
COPY conf/nginx-site.conf /etc/nginx/sites-available/default.conf

# Render/Laravel env
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]
