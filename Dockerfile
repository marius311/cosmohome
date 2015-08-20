FROM marius311/boincserver_boinc

RUN apt-get install -y wget unzip vim

COPY .bashrc /root/

RUN ./make_project --url_base http://beta.cosmologyathome.org \
                   --html_user_url http://beta.cosmologyathome.org \
                   --project_host cosmohome \
                   --no_db \
                   --no_query \
                   cosmohome

ENV PROJHOME=/root/projects/cosmohome
ENV TMP=/tmp

# setup boinc2docker
COPY boinc2docker $TMP/boinc2docker
RUN cd $TMP/boinc2docker && ./setup_versions 26169

# install boinc2docker_camb
COPY camb_boinc2docker/boinc/ $PROJHOME
RUN cd $TMP/boinc2docker && ./install_as $PROJHOME camb_boinc2docker 1.0 $PROJHOME/apps_boinc2docker/camb/vbox_job.xml

# install camb_legacy
COPY camb_legacy/ $PROJHOME

# project files
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf $PROJHOME/
COPY html $PROJHOME/html


# repare for running cosmohome_init
RUN mv /root/projects /root/projects.build
COPY cosmohome_init.py /root/
