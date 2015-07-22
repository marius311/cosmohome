FROM marius311/boincserver_boinc

RUN ./make_project --url_base http://www.cosmologyathome.org \
                   --html_user_url http://www.cosmologyathome.org \
                   --project_host boinc-server \
                   --db_host mysql \
                   --db_passwd passwd \
                   --no_db \
                   --no_query \
                   cosmohome

RUN mv /root/projects /root/projects.build            

COPY cosmohome_init.py /root/
