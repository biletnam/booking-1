# Build with: docker build -t lamp .
#   Run with: docker run -itp 80:80 lamp

FROM ubuntu:latest
MAINTAINER Alexis Nootens <me@axn.io>

ENV DEBIAN_FRONTEND noninteractive
ENV MYSQL_PWD root

WORKDIR /

RUN mkdir -p opt/mysql/mysql opt/mysql/mysql/data var/www/app

# Because stdin is closed, every password prompts will be answered with 'root'
RUN echo 'mysql-server-5.7 mysql-server/root_password password root' | debconf-set-selections && \
    echo 'mysql-server-5.7 mysql-server/root_password_again password root' | debconf-set-selections && \
    apt-get -qq update && \
    apt-get -qq install apache2 mysql-server-5.7 php7.0 libapache2-mod-php7.0 > /dev/null && \
    mysqld --initialize \
           --explicit_defaults_for_timestamp \
           --basedir=/opt/mysql/mysql \
           --datadir=/opt/mysql/mysql/data

# Fix apache AH00558
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Fix mysql no home
RUN usermod -d /var/lib/mysql/ mysql

RUN echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/mysql/admin-user string root' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/mysql/admin-pass password root' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/mysql/app-pass password root' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/app-password-confirm password root' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/reconfigure-websever multiselect apache2' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/database-type select mysql' | debconf-set-selections && \
    echo 'phpmyadmin phpmyadmin/setup-password password root' | debconf-set-selections && \
    apt-get -qq install phpmyadmin > /dev/null && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Fix mysql bug #16102788 — commit 3d8b4570d1a9d8d03d32e4cd6705b6a2d354e992
# Since mysql 5.7, an empty port cause an error and phpmyadmin still uses an empty
# port to connect thus being unable to create the phpmyadmin database in mysql
RUN service mysql start && \
    mysql -e "CREATE DATABASE phpmyadmin;" && \
    mysql --database=phpmyadmin < /usr/share/phpmyadmin/sql/create_tables.sql && \
    sed -i 's/dbuser=\x27phpmyadmin\x27/dbuser=\x27root\x27/g' /etc/phpmyadmin/config-db.php && \
    sed -i 's/dbport=\x27\x27/dbport=\x273306\x27/g' /etc/phpmyadmin/config-db.php && \
    dpkg-reconfigure phpmyadmin

# Enable phpMyAdmin
RUN cat /etc/phpmyadmin/apache.conf >> /etc/apache2/apache2.conf

# Apache Configuration
RUN sed -i 's/html/app/g' /etc/apache2/sites-available/000-default.conf && \
    sed -i '166s/None/All/' /etc/apache2/apache2.conf && \
    a2enmod rewrite

EXPOSE 80 443
CMD service mysql start && apachectl start && tail -f /var/log/apache2/access.log
