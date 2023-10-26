# Sử dụng image PHP của Alpine
FROM php:8.2-fpm-alpine

# Tao folder chua log cua supervisor
WORKDIR /var/www/backend-php-0823-1

# Cài đặt các gói phần mềm bằng apk
RUN apk update && apk add --no-cache \
    build-base \
    libpng-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zlib-dev \
    libxslt-dev \
    autoconf \
    curl \
    git \
    vim \
    unzip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    supervisor \
    net-tools \
    && rm -rf /var/cache/apk/*

# Cài đặt các extension PHP bằng Docker PHP extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install gd \
    && docker-php-ext-install xsl

# Sử dụng tệp cấu hình PHP mặc định cho môi trường production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Tao non-root user
ARG HOST_UID
ARG HOST_GID

RUN addgroup -g ${HOST_GID} g0823 \
 && adduser -D -H -S -s /sbin/nologin -G g0823 -u ${HOST_UID} u0823

# Sao chép tệp cấu hình 
COPY --chown=u0823:g0823 .docker/dev/supervisord.conf /etc/supervisord.conf
COPY --chown=u0823:g0823 .docker/dev/supervisor.d /etc/supervisor.d
COPY --chown=u0823:g0823 .docker/dev/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Sao chép nội dung thư mục ứng dụng
COPY --chown=u0823:g0823 . /var/www/backend-php-0823-1

# Tao folder chua logs cua supervisor
RUN mkdir -p /var/www/storage/logs/ && \
    chown -R u0823:g0823 /var/www/storage

# Sử dụng người dùng u0823 
USER u0823

# CMD php artisan key:generate && php artisan migrate --force && php artisan serve --host app --port 8000
# CMD php artisan migrate --force  && supervisord -n -c /etc/supervisord.conf
# ENTRYPOINT [ ".docker/dev/php/entrypoint.sh" ]
# CMD supervisord -n -c /etc/supervisord.conf && php artisan migrate --force
CMD supervisord -n -c /etc/supervisord.conf