FROM marius311/boincserver_boinc

RUN apt-get install -y wget unzip vim

COPY .bashrc /root/

RUN ./make_project --url_base http://www.cosmologyathome.org \
                   --html_user_url http://www.cosmologyathome.org \
                   --project_host cosmohome \
                   --db_host mysql \
                   --db_passwd passwd \
                   --no_db \
                   --no_query \
                   cosmohome

ENV PROJHOME=/root/projects/cosmohome
ENV TMP=/tmp

# setup boinc2docker
COPY boinc2docker $TMP/boinc2docker
RUN cd $TMP/boinc2docker && ./setup_versions 26169


# install boinc2docker_camb
RUN cd $TMP/boinc2docker && ./install_as $PROJHOME camb_boinc2docker 1.0
COPY camb_boinc2docker/boinc/ $PROJHOME

# project files
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf $PROJHOME/
COPY html $PROJHOME/html

# repare for running cosmohome_init
RUN mv /root/projects /root/projects.build
COPY cosmohome_init.py /root/
