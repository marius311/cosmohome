FROM boinc/server_apache:2.1.0-b2d

MAINTAINER Marius Millea <mariusmillea@gmail.com>

# install packages 
RUN echo "deb http://ftp.debian.org/debian jessie-backports main" >> /etc/apt/sources.list \
    && apt-get update && apt-get install -y --no-install-recommends \
        python-matplotlib \
        python-mysqldb \
        python-numpy \
        python-scipy \
        python-yaml \
        vim \
    && apt-get install -y --no-install-recommends -t jessie-backports python-certbot-apache

RUN a2enmod ssl

# get xkcd font
RUN curl -L http://antiyawn.com/uploads/Humor-Sans-1.0.ttf > /usr/share/matplotlib/mpl-data/fonts/ttf/Humor-Sans.ttf

RUN rm /etc/apache2/sites-enabled/000-default.conf

COPY supervisor.cosmohome.conf /etc/supervisor/conf.d
