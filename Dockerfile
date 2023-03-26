
FROM php:8-alpine

RUN apk add doas; \
    adduser -D $USER; \
        echo 'permit $USER as root' > /etc/doas.d/doas.conf
USER $USER

RUN whoami


COPY . /prestacli

WORKDIR /prestacli

VOLUME /prestacli
