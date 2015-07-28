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

# install boinc2docker
COPY boinc2docker/apps/boinc2docker/1.0/example $PROJHOME/apps/boinc2docker/1.0/example
COPY boinc2docker/templates                     $PROJHOME/templates
COPY boinc2docker/setup_versions.sh             $PROJHOME/
RUN cd $PROJHOME && \
    ./setup_versions.sh 26169 && \
    rm -r setup_versions.sh $PROJHOME/apps/boinc2docker/1.0/example


# install boinc2docker_camb
COPY boinc2docker_camb/boinc/apps_boinc2docker $PROJHOME/apps_boinc2docker
COPY boinc2docker_camb/boinc/bin               $PROJHOME/bin


# project files
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf $PROJHOME/
COPY html $PROJHOME/html

# repare for running cosmohome_init
RUN mv /root/projects /root/projects.build
COPY cosmohome_init.py /root/
