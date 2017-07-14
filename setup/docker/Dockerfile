FROM oacis/oacis:latest

#MAINTAINER kamiya <k14041kk@aitech.ac.jp> ##deprecated

EXPOSE 3080 3000

USER root
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update -y
RUN apt-get  -y install apt-utils

RUN echo deb http://packages.dotdeb.org jessie all >> /etc/apt/sources.list
RUN wget -qO - https://www.dotdeb.org/dotdeb.gpg | apt-key add -
RUN apt-get -y update 
RUN apt-get -y install php php-mbstring php7.0-zip sqlite3 php7.0-sqlite3 php7.0-mongodb sudo perl nginx php-fpm
RUN apt-get -y remove apache2
RUN apt-get -y autoremove
RUN apt-get -y autoclean

RUN usermod -aG sudo oacis

#Perl
ENV PERL_MM_USE_DEFAULT 1
RUN cpan -i DBI
RUN cpan -i DBD::SQLite

#Config files
COPY supplement/rrsoacis_fpm.conf /etc/php/7.0/fpm/pool.d/
COPY supplement/rrsoacis_fpm_php.ini /etc/php/7.0/fpm/conf.d/
COPY supplement/rrsoacis_nginx.conf /etc/nginx/conf.d/
RUN sed -iE 's/www-data/oacis/' /etc/nginx/nginx.conf

#Updater
COPY supplement/update-rrsoacis /usr/local/bin/
RUN chmod a+x /usr/local/bin/update-rrsoacis

#RRS-OACIS
USER oacis
WORKDIR /home/oacis
RUN git clone https://github.com/tkmnet/rrs-oacis.git
RUN chmod a+x ./rrs-oacis/setup.sh
RUN cp ./rrs-oacis/setup/docker/supplement/server.sh ./rrs-oacis/server.sh
RUN chmod a+x ./rrs-oacis/server.sh

WORKDIR /home/oacis/rrs-oacis
RUN git clone https://github.com/tkmnet/rrsenv.git
RUN ./rrsenv/init.sh

#Mount host volumes
#VOLUME ["/home/oacis/rrs-oacis/data"]
#VOLUME ["/home/oacis/rrs-oacis/rrsenv/workspace"]
#VOLUME ["/home/oacis/rrs-oacis/rrsenv/AGENT"]
#VOLUME ["/home/oacis/rrs-oacis/rrsenv/MAP"]
#VOLUME ["/home/oacis/rrs-oacis/rrsenv/LOG"]

#Setup
USER oacis
RUN /home/oacis/rrs-oacis/setup.sh

USER root
WORKDIR /home/oacis
CMD ["/home/oacis/rrs-oacis/server.sh"]

