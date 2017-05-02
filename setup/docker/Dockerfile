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
RUN apt-get -y install php php-mbstring php7.0-zip sqlite3 php7.0-sqlite3

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
USER root
RUN /home/oacis/rrs-oacis/setup.sh

WORKDIR /home/oacis
CMD ["/home/oacis/rrs-oacis/server.sh"]

