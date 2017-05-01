FROM oacis/oacis:latest

#MAINTAINER kamiya <k14041kk@aitech.ac.jp> ##deprecated

EXPOSE 3080

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

#RRS-OACIS
USER oacis
WORKDIR /home/oacis
RUN git clone https://github.com/tkmnet/rrs-oacis.git
COPY supplement/server.sh ./rrs-oacis/

WORKDIR /home/oacis/rrs-oacis
RUN git clone https://github.com/tkmnet/rrsenv.git
RUN ./rrsenv/init.sh

#Composer
USER root
RUN /home/oacis/rrs-oacis/setup.sh
WORKDIR /

USER root
WORKDIR /home/oacis
CMD ["/home/oacis/rrs-oacis/server.sh"]

