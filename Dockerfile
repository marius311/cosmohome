FROM boinc/server_makeproject:latest-b2d

MAINTAINER Marius Millea <mariusmillea@gmail.com>

#dont need built-in boinc2docker app
RUN rm -rf $PROJHOME/apps/boinc2docker $PROJHOME/log_boincserver

#install extra packages
RUN apt-get update && apt-get install -y \
        ruby-kramdown \
        unzip \
        vim \
        wget


# install camb_legacy
COPY apps/camb_legacy/ $PROJHOME

# install boinc2docker_camb
COPY apps/camb_boinc2docker/boinc/ $PROJHOME
RUN cd /root/boinc2docker && ./boinc2docker_create_app $PROJHOME/apps_boinc2docker/camb/boinc2docker.yml

# install lsplitsims
COPY apps/lsplitsims/ $PROJHOME
RUN cd /root/boinc2docker && ./boinc2docker_create_app $PROJHOME/apps_boinc2docker/lsplitsims/boinc2docker.yml


# sign executables
COPY keys $PROJHOME/keys
RUN for f in `find $PROJHOME/apps/ -type f -not -name "version.xml"`; do \
      /root/boinc/tools/sign_executable $f $PROJHOME/keys/code_sign_private > ${f}.sig; \
    done \
    && rm $PROJHOME/keys/code_sign_private

# project files
COPY py $PROJHOME/py
COPY project.xml config.xml boinc2docker/plan_class_spec.xml boincserver.httpd.conf db_dump_spec.xml $PROJHOME/
COPY html $PROJHOME/html

# compile markdown files
RUN cd $PROJHOME/html/user && ./compile_md.py

# finish up
ARG GITTAG
RUN echo $GITTAG > $PROJHOME/.gittag
