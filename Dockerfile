FROM boinc/server_makeproject:1.4.2-b2d

MAINTAINER Marius Millea <mariusmillea@gmail.com>

#dont need built-in boinc2docker app
RUN rm -rf $PROJHOME/apps/boinc2docker

#install extra packages
RUN apt-get update && apt-get install -y ruby-kramdown

# install camb_legacy
COPY apps/camb_legacy/ $PROJHOME

# install boinc2docker_camb
COPY apps/camb_boinc2docker/ $PROJHOME
RUN boinc2docker_create_app $PROJHOME/apps_boinc2docker/camb/boinc2docker.yml

# install lsplitsims
COPY apps/lsplitsims/ $PROJHOME
RUN boinc2docker_create_app $PROJHOME/apps_boinc2docker/lsplitsims/boinc2docker.yml

# project files
COPY py $PROJHOME/py
COPY project.xml config.xml boincserver.httpd.conf $PROJHOME/
COPY html $PROJHOME/html
COPY bin $PROJHOME/bin

# private files and signing
COPY private/ $PROJHOME/
RUN bin/sign_all_apps \
    && bin/xml_merge --cleanup config.private.yml config.xml

# compile markdown files
RUN cd $PROJHOME/html/user && ./compile_md.py

# finish up
ARG GITTAG
RUN echo $GITTAG > $PROJHOME/.gittag
