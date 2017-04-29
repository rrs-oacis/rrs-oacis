FROM oacis/oacis

MAINTAINER kamiya <k14041kk@aitech.ac.jp>

ENV DEBIAN_FRONTEND noninteractive

#PHP install
RUN apt-get -y -f remove libzip2

RUN apt-get update -y

#URL=>http://obel.hatenablog.jp/entry/20160311/1457644814
RUN echo deb http://packages.dotdeb.org jessie all >> /etc/apt/sources.list
RUN wget https://www.dotdeb.org/dotdeb.gpg
RUN apt-key add dotdeb.gpg
RUN apt-get -y update 
RUN apt-get -y install php
RUN apt-get -y install php-mbstring
RUN apt-get -y install libzip2
RUN apt-get -y install php7.0-zip

#ADF
RUN mkdir /home/oacis/adf
RUN mkdir /home/oacis/adf/src
COPY src /home/oacis/adf/src/

RUN mkdir /home/oacis/adf/ruby
COPY ruby /home/oacis/adf/ruby/

RUN mkdir /home/oacis/adf/public
COPY public /home/oacis/adf/public/
COPY composer.json /home/oacis/adf/
COPY setup.sh /home/oacis/adf/
COPY docker_php_server.sh /home/oacis/adf/
COPY php.ini /home/oacis/adf/
COPY server_r_p.sh /home/oacis/adf/

#PHP Setup
USER root
#WORKDIR /home/oacis/adf
RUN /home/oacis/adf/setup.sh
WORKDIR /

RUN mkdir /home/oacis/adf/rrsenv
RUN mkdir /home/oacis/adf/rrsenv/MAP
RUN mkdir /home/oacis/adf/rrsenv/AGENT

EXPOSE 6040

VOLUME /home/oacis/adf/src
VOLUME /home/oacis/adf/public
VOLUME /home/oacis/adf/ruby

WORKDIR /home/oacis/adf
CMD ["./docker_php_server.sh"]

