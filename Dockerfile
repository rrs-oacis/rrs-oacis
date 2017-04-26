FROM ruby:2.3.3

MAINTAINER kamiya <k14041kk@aitech.ac.jp>

#PHP install
RUN apt-get update -y

#URL=>http://obel.hatenablog.jp/entry/20160311/1457644814
RUN echo deb http://packages.dotdeb.org jessie all >> /etc/apt/sources.list
RUN wget https://www.dotdeb.org/dotdeb.gpg
RUN apt-key add dotdeb.gpg
RUN apt-get update -y
RUN apt-get install -y php
RUN apt-get -y install php-mbstring
RUN apt-get -y install php7.0-zip

#ADF
RUN mkdir /adf
RUN mkdir /adf/src
COPY src /adf/src/

RUN mkdir /adf/public
COPY public /adf/public/
COPY composer.json /adf/
COPY setup.sh /adf/
COPY docker_php_server.sh /adf/
COPY php.ini /adf/

#PHP Setup
USER root
WORKDIR /adf
RUN ./setup.sh
WORKDIR /

VOLUME /adf/src
VOLUME /adf/public


CMD ["/adf/docker_php_server.sh"]

EXPOSE 6040