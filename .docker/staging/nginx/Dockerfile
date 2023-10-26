FROM nginx:alpine

WORKDIR /var/www/backend-php-0823-1

# Tao non-root user
ARG HOST_UID
ARG HOST_GID


RUN addgroup -g ${HOST_GID} g0823 \
 && adduser -D -H -S -s /sbin/nologin -G g0823 -u ${HOST_UID} u0823

RUN rm -r /etc/nginx/conf.d/*
COPY conf.d /etc/nginx/conf.d
COPY nginx.conf /etc/nginx/nginx.conf
COPY ssl/cert.pem /etc/ssl/certs/cert.pem
COPY ssl/key.pem /etc/ssl/private/key.pem
COPY ssl/cloudflare.crt /etc/ssl/certs/cloudflare.crt


RUN mkdir -p /var/cache/nginx \
    && mkdir -p /var/log/nginx \
    # Chinh sua perm va owner
    && chown -R u0823:g0823 /var/www/backend-php-0823-1 \
    && chmod -R 755 /var/www/backend-php-0823-1 \
    && chown -R u0823:g0823 /var/cache/nginx \
    # Change owner of log
    && chown -R u0823:g0823 /var/log/nginx \
    # Change ower of config
    && chown -R u0823:g0823 /etc/nginx/conf.d \
    && chown    u0823:g0823 /etc/nginx/nginx.conf

RUN touch /var/run/nginx.pid  \
    # && chown -R u0823:g0823 /var/run/nginx.pid
    && chown u0823:g0823 /var/run/nginx.pid

USER u0823