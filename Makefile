
default:


# -- cosmohome ---

build-cosmohome: 
    docker build -t marius311/cosmohome .

run-cosmohome:
    docker run --link cosmohome_mysql:mysql \
               --env-file=db_passwd.env \
               --volumes-from=cosmohomedata \
               --hostname=cosmohome \
               --name cosmohome \
               --rm \
               -it \
               marius311/cosmohome \
               $(CMD)

rm-cosmohome:
    -docker rm -f cosmohome               

create-cosmohomedata:
    docker create -v /root/projects -v /etc/apache2/sites-enabled \
                  --name=cosmohomedata \
                  --entrypoint true \
                  marius311/cosmohome

rm-cosmohomedata:
    -docker rm -f $(ARGS) cosmohomedata

#--- apache ---

run-apache:
    docker run -p "80:80" -d \
               --link cosmohome_mysql:mysql \
               --volumes-from=cosmohomedata \
               --hostname=cosmohome \
               --name=cosmohome_apache \
               marius311/boincserver_apache

rm-apache:
    -docker rm -f cosmohome_apache  

exec-apache:
    docker exec -it cosmohome_apache /bin/bash



# --- mysql ---

create-mysqldata:
    docker create -v /var/lib/mysql \
                  --name=cosmohome_mysqldata \
                  --entrypoint true \
                  mysql:5.6.25 


rm-mysqldata:
    -docker rm cosmohome_mysqldata

run-mysql:
    docker run --volumes-from cosmohome_mysqldata \
               --name cosmohome_mysql \
               --env-file db_passwd.env \
               -d \
               mysql:5.6.25

rm-mysql:
    -docker rm -f cosmohome_mysql


rm: rm-mysql rm-mysqldata rm-apache rm-cosmohomedata rm-cosmohome
