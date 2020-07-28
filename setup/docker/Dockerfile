FROM oacis/oacis:latest

ENV DEBIAN_FRONTEND noninteractive
RUN echo deb http://packages.dotdeb.org stretch all \
    >> /etc/apt/sources.list
RUN curl --fail --silent --show-error \
    --location https://www.dotdeb.org/dotdeb.gpg | apt-key add -

RUN apt-get update && \
    apt-get install --assume-yes \
        nginx php php-fpm perl sqlite3 default-jdk zip unzip \
        libnet-openssh-perl libio-pty-perl \
        libjson-perl libdbd-sqlite3-perl \
        php-mbstring php-zip \
        php7.0-sqlite3 php7.0-mongodb && \
    apt-get clean

# Nginx & PHP
RUN mkdir --parents /run/php
RUN sed --in-place 's/www-data/oacis/' /etc/nginx/nginx.conf
COPY supplement/rrsoacis_nginx.conf /etc/nginx/conf.d/
COPY supplement/rrsoacis_fpm.conf /etc/php/7.0/fpm/pool.d/
COPY supplement/rrsoacis_fpm_php.ini /etc/php/7.0/fpm/conf.d/

# rrs-oacis/rrs-oacis
USER oacis
WORKDIR /home/oacis
RUN git clone https://github.com/rrs-oacis/rrs-oacis.git
COPY --chown=oacis:oacis supplement/server.sh rrs-oacis/
COPY --chown=oacis:oacis supplement/update-rrsoacis /usr/local/bin/
RUN chmod u+x rrs-oacis/setup/setup.sh
RUN chmod u+x rrs-oacis/server.sh
RUN chmod u+x /usr/local/bin/update-rrsoacis
RUN ./rrs-oacis/setup/setup.sh

# tkmnet/rrsenv
WORKDIR /home/oacis/rrs-oacis
RUN git clone https://github.com/tkmnet/rrsenv.git
COPY --chown=oacis:oacis supplement/alt_rrsenv_init.sh rrsenv/
RUN chmod u+x rrsenv/alt_rrsenv_init.sh
RUN ./rrsenv/alt_rrsenv_init.sh

# Volume
RUN mkdir data
VOLUME ["/home/oacis/rrs-oacis/data"]
VOLUME ["/home/oacis/rrs-oacis/rrsenv/workspace"]
VOLUME ["/home/oacis/rrs-oacis/rrsenv/AGENT"]
VOLUME ["/home/oacis/rrs-oacis/rrsenv/MAP"]
VOLUME ["/home/oacis/rrs-oacis/rrsenv/LOG"]

USER root
WORKDIR /home/oacis
EXPOSE 3080 3000
CMD ["./rrs-oacis/server.sh"]
