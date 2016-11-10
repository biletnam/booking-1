#
# Build with: docker build -t lamp .
#   Run with: docker run -itp 80:80 lamp

FROM ubuntu:latest
MAINTAINER Alexis Nootens <me@axn.io>

ENV DEBIAN_FRONTEND noninteractive

WORKDIR /

RUN mkdir -p opt/mysql/mysql opt/mysql/mysql/data

RUN debconf-set-selections << 'mysql-server mysql-server/root_password password' && \
    debconf-set-selections << 'mysql-server mysql-server/root_password_again password' && \
    apt-get -qq update && \
    apt-get -y install apache2 mysql-server php7.0 libapache2-mod-php7.0 > /dev/null && \
    mysqld --initialize \
           --explicit_defaults_for_timestamp \
           --basedir=/opt/mysql/mysql \
           --datadir=/opt/mysql/mysql/data

# Fix AH00558
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN sed -i 's/html//g' /etc/apache2/sites-available/000-default.conf
COPY . /var/www

EXPOSE 80 443
CMD apachectl start && tail -f /var/log/apache2/access.log
