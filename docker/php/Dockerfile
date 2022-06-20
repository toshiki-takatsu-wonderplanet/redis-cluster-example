# php7.4 on Amazonlinux
FROM amazonlinux:2 as base

RUN set -eux \
    && yum update -y \
    && amazon-linux-extras install -y php7.4 \
    && yum install -y \
        git \
        unzip \
        php-pecl-redis \
    && yum clean all && rm -rf /var/cache/yum

### library build stage
FROM base as build-lib

RUN set -eux \
    && yum update -y \
    && yum install -y \
        tar gcc make \
        php-devel \
        php-pear \
    && yum clean all && rm -rf /var/cache/yum \
    && pecl channel-update "pecl.php.net" \
    && pecl install xdebug \
    && pecl clear-cache && rm -rf /tmp/pear ~/.pearrc

## build target
FROM base as develop

# timezone environment
ENV TZ=Asia/Tokyo \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8

COPY ./docker-php-entrypoint /usr/local/bin/

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

COPY ./php.development.ini /etc/php.ini

# xdebug
COPY --from=build-lib /usr/lib64/php/modules/xdebug.so /usr/lib64/php/modules/
# You should add "zend_extension=/usr/lib64/php/modules/xdebug.so" to php.ini
RUN set -eux; \
  echo 'zend_extension=xdebug.so' > /etc/php.d/20-xdebug.ini

WORKDIR /app

ENTRYPOINT ["docker-php-entrypoint"]
CMD ["php", "-a"]
