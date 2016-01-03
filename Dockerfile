FROM debian:jessie

MAINTAINER Marius Millea <mariusmillea@gmail.com>

#install necessary packages
RUN apt-get update && apt-get install -y \
        curl \
        dh-autoreconf \
        git \
        libcurl4-gnutls-dev \
        libmysqlclient-dev \
        libssl-dev \
        m4 \
        make \
        php5-cli \
        php5-gd \
        php5-mysql \
        pkg-config \
        python \
        python-mysqldb \
        ruby-kramdown \
        unzip \
        vim \
        wget

#get source and compile server
COPY boinc /root/boinc
WORKDIR /root/boinc
RUN ./_autosetup && ./configure --disable-client --disable-manager && make

ENV HOME=/root
ENV USER=root

#prettier shell when exec-ing in
COPY .bashrc /root/

#needed for boinc
RUN echo "umask 0002" >> /root/.bashrc


ENV PROJHOME=/root/projects/cosmohome
ENV TMP=/tmp

#make project
RUN mkdir -p /root/projects.build && ln -s /root/projects.build /root/projects
WORKDIR /root/boinc/tools
RUN ./make_project --url_base http://www.cosmologyathome.org \
                   --html_user_url http://www.cosmologyathome.org \
                   --project_host cosmohome \
                   --db_host mysql \
                   --db_user root \
                   --no_db \
                   --no_query \
                   cosmohome

WORKDIR /root

# setup boinc2docker
COPY boinc2docker $PROJHOME/boinc2docker
COPY .git/modules/boinc2docker $PROJHOME/.git/modules/boinc2docker
RUN cd $PROJHOME/boinc2docker && ./setup_versions

# install boinc2docker_camb
COPY camb_boinc2docker/boinc/ $PROJHOME
RUN cd $PROJHOME/boinc2docker && ./install_as $PROJHOME camb_boinc2docker 0.08 $PROJHOME/apps_boinc2docker/camb/vbox_job.xml

# install camb_legacy
COPY camb_legacy/ $PROJHOME

# project files
COPY keys $PROJHOME/keys
RUN mkdir $PROJHOME/html/stats_archive
COPY py $PROJHOME/py
COPY .git $PROJHOME/.git
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf db_dump_spec.xml $PROJHOME/
COPY html $PROJHOME/html

# compile markdown files
RUN cd /root/projects.build/cosmohome/html/user && ./compile_md.py

# repare for running cosmohome_init
RUN rm $PROJHOME/keys/*private /root/projects
COPY cosmohome_postbuild.py /root/
