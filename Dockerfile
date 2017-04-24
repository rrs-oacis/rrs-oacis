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

#ADF
RUN mkdir /adf
RUN mkdir /adf/src
COPY src /adf/src/

RUN mkdir /adf/public
COPY public /adf/public/
COPY composer.json /adf/
COPY setup.sh /adf/
COPY server.sh /adf/
COPY php.ini /adf/


WORKDIR /adf
RUN ./setup.sh
WORKDIR /

CMD ["/adf/server.sh"]

EXPOSE 6040