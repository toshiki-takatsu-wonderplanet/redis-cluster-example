# php7.4 on Amazonlinux
FROM amazonlinux:2

# timezone environment
ENV TZ=Asia/Tokyo \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8

RUN set -eux \
    && yum update -y \
    && yum install -y \
        git \
        unzip \
    && amazon-linux-extras install -y php7.4 \
    && yum clean all && rm -rf /var/cache/yum

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

COPY ./php.development.ini /etc/php.ini

WORKDIR /app
