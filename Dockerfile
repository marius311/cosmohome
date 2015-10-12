FROM marius311/boincserver_boinc

ENV PROJHOME=/root/projects/cosmohome
ENV TMP=/tmp

RUN apt-get install -y wget unzip vim git

COPY .bashrc /root/

RUN mkdir -p /root/projects.build && ln -s /root/projects.build /root/projects

RUN ./make_project --url_base http://beta.cosmologyathome.org \
                   --html_user_url http://beta.cosmologyathome.org \
                   --project_host cosmohome \
                   --no_db \
                   --no_query \
                   cosmohome

# setup boinc2docker
COPY boinc2docker $PROJHOME/boinc2docker
COPY .git/modules/boinc2docker $PROJHOME/.git/modules/boinc2docker
RUN cd $PROJHOME/boinc2docker && ./setup_versions

# install boinc2docker_camb
COPY camb_boinc2docker/boinc/ $PROJHOME
RUN cd $PROJHOME/boinc2docker && ./install_as $PROJHOME camb_boinc2docker 0.04 $PROJHOME/apps_boinc2docker/camb/vbox_job.xml

# install camb_legacy
COPY camb_legacy/ $PROJHOME

# project files
COPY project.xml config.xml boinc2docker/plan_class_spec.xml cosmohome.httpd.conf $PROJHOME/
COPY html $PROJHOME/html
COPY keys $PROJHOME/keys
COPY py $PROJHOME/py
COPY .git $PROJHOME/.git

# repare for running cosmohome_init
RUN rm /root/projects
COPY cosmohome_postbuild.py /root/
