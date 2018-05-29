FROM boinc/server_makeproject:2.1.0-b2d

MAINTAINER Marius Millea <mariusmillea@gmail.com>

#dont need built-in boinc2docker app
RUN rm -rf $PROJECT_ROOT/apps/boinc2docker

#install extra packages
RUN apt-get update && apt-get install -y --no-install-recommends ruby-kramdown

# install camb_legacy
COPY apps/camb_legacy/ $PROJECT_ROOT

# install boinc2docker_camb
COPY apps/camb_boinc2docker/ $PROJECT_ROOT
RUN boinc2docker_create_app --projhome $PROJECT_ROOT $PROJECT_ROOT/apps_boinc2docker/camb/boinc2docker.yml

# install lsplitsims
COPY apps/lsplitsims/ $PROJECT_ROOT
RUN boinc2docker_create_app --projhome $PROJECT_ROOT $PROJECT_ROOT/apps_boinc2docker/lsplitsims/boinc2docker.yml

# project files
COPY py $PROJECT_ROOT/py
COPY project.xml config.xml cosmohome.httpd.conf $PROJECT_ROOT/
COPY boinc/html $PROJECT_ROOT/html
COPY html $PROJECT_ROOT/html
COPY bootswatch/cyborg/bootstrap.min.css $PROJECT_ROOT/html/user
COPY bin $PROJECT_ROOT/bin


# private files and signing
COPY private/ $PROJECT_ROOT/
RUN bin/sign_all_apps \
    && bin/xml_merge --cleanup config.private.yml config.xml

# compile markdown files
RUN cd $PROJECT_ROOT/html/user && ./compile_md.py

# finish up
ARG GITTAG
RUN echo $GITTAG > $PROJECT_ROOT/.gittag
