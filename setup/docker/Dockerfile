FROM oacis/oacis:latest

#MAINTAINER kamiya <k14041kk@aitech.ac.jp> ##deprecated

EXPOSE 3080 3000

ENV DEBIAN_FRONTEND noninteractive

USER root
RUN apt-get update -y
RUN apt-get  -y install apt-utils

RUN echo deb http://packages.dotdeb.org jessie all >> /etc/apt/sources.list
RUN wget https://www.dotdeb.org/dotdeb.gpg
RUN apt-key add dotdeb.gpg
RUN apt-get -y update 
RUN apt-get -y install php
RUN apt-get -y install php-mbstring
RUN apt-get -y install php7.0-zip

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

#Setup
USER root
RUN /home/oacis/rrs-oacis/setup.sh
WORKDIR /

USER root
WORKDIR /home/oacis
CMD ["/home/oacis/rrs-oacis/server.sh"]

