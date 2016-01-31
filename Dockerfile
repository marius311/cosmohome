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
        python-scipy \
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
RUN cd $PROJHOME/boinc2docker \
    && ISOTAG=v0.43 VBOXTAG=v0.6 ./setup_versions

# install camb_legacy
COPY apps/camb_legacy/ $PROJHOME

# install boinc2docker_camb
COPY apps/camb_boinc2docker/boinc/ $PROJHOME
RUN cd $PROJHOME/boinc2docker && ./install_as $PROJHOME camb_boinc2docker 1.00 $PROJHOME/apps_boinc2docker/camb/vbox_job.xml

# install lsplitsims
COPY apps/lsplitsims/ $PROJHOME
RUN cd $PROJHOME/boinc2docker && ./install_as $PROJHOME lsplitsims 1.00 $PROJHOME/apps_boinc2docker/lsplitsims/vbox_job.xml


# sign executables
COPY keys $PROJHOME/keys
RUN for f in `find $PROJHOME/apps/ -type f -not -name "version.xml"`; do \
      /root/boinc/tools/sign_executable $f $PROJHOME/keys/code_sign_private > ${f}.sig; \
    done \
    && rm $PROJHOME/keys/code_sign_private

# project files
RUN mkdir $PROJHOME/html/stats_archive
COPY py $PROJHOME/py
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf db_dump_spec.xml $PROJHOME/
COPY html $PROJHOME/html
COPY .git $PROJHOME/.git

# compile markdown files
RUN cd /root/projects.build/cosmohome/html/user && ./compile_md.py

# repare for running cosmohome_init
RUN rm /root/projects
COPY cosmohome_postbuild.py /root/
