FROM oacis/oacis:latest
#FROM ruby:2.3.3

MAINTAINER kamiya <k14041kk@aitech.ac.jp>

ENV DEBIAN_FRONTEND noninteractive

USER root
RUN apt-get update -y
RUN apt-get  -y install apt-utils

#URL=>http://obel.hatenablog.jp/entry/20160311/1457644814
RUN echo deb http://packages.dotdeb.org jessie all >> /etc/apt/sources.list
RUN wget https://www.dotdeb.org/dotdeb.gpg
RUN apt-key add dotdeb.gpg
RUN apt-get -y update 
RUN apt-get -y install php
RUN apt-get -y install php-mbstring
RUN apt-get -y install php7.0-zip

#RRS-OACIS
USER oacis
RUN mkdir /home/oacis/rrs-oacis
RUN mkdir /home/oacis/rrs-oacis/src
COPY src /home/oacis/rrs-oacis/src/

RUN mkdir /home/oacis/rrs-oacis/ruby
COPY ruby /home/oacis/rrs-oacis/ruby/

RUN mkdir /home/oacis/rrs-oacis/public
COPY public /home/oacis/rrs-oacis/public/
COPY composer.json /home/oacis/rrs-oacis/
COPY setup.sh /home/oacis/rrs-oacis/
RUN chmod a+x /home/oacis/rrs-oacis/setup.sh
COPY server.sh /home/oacis/rrs-oacis/
RUN chmod a+x /home/oacis/rrs-oacis/server.sh
COPY php.ini /home/oacis/rrs-oacis/

WORKDIR /home/oacis/rrs-oacis
RUN git clone https://github.com/tkmnet/rrsenv.git
RUN ./rrsenv/init.sh

#PHP Setup
USER root
RUN /home/oacis/rrs-oacis/setup.sh
WORKDIR /

EXPOSE 3080

VOLUME /home/oacis/rrs-oacis/src
VOLUME /home/oacis/rrs-oacis/public
VOLUME /home/oacis/rrs-oacis/ruby

WORKDIR /home/oacis/rrs-oacis
CMD ["./server.sh"]

