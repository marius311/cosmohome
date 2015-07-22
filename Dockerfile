FROM marius311/boincserver_boinc

COPY boinc /root/boinc

RUN ./make_project --url_base http://www.cosmologyathome.org \
                   --html_user_url http://www.cosmologyathome.org \
                   --project_host boinc-server \
                   --db_host mysql \
                   --db_passwd passwd \
                   --no_db \
                   --no_query \
                   cosmohome

RUN chmod -R g+w /root/projects/cosmohome/html/cache

#TODO: edit this and include
# COPY cosmohome.httpd.conf /root/projects/cosmohome/                   

RUN mv /root/projects /root/projects.build            

COPY cosmohome_init.py /root/
