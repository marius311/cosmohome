default:


# -- cosmohome ---

build-cosmohome: 
	mkdir -p keys
	(cd boinc2docker && git describe --tags --abbrev=0 > .tag)
	docker build -t cosmohome .

run-cosmohome:
	docker run --link cosmohome_mysql:mysql \
   			   --env-file=db_passwd.env \
   			   --volumes-from=cosmohome_projectdata \
   			   --volumes-from=cosmohome_resultdata \
   			   --hostname=cosmohome \
   			   --name cosmohome \
   			   --rm \
   			   -it \
   			   cosmohome \
   			   $(CMD)

rm-cosmohome:
	-docker rm -f cosmohome

download-private-data:
	git archive --remote=ssh://git@bitbucket.org/marius311/cosmohome_private.git --format=tar master | tar xvf -

# --- cosmohome data-only containers ---

create-projectdata:
	docker create -v /root/projects -v /etc/apache2/sites-enabled \
			      --name=cosmohome_projectdata \
			      --entrypoint true \
			      cosmohome

rm-projectdata:	
	-docker rm -vf $(ARGS) cosmohome_projectdata

create-resultdata:
	docker create -v /root/results \
			      --name=cosmohome_resultdata \
			      --entrypoint true \
			      cosmohome

rm-resultdata:	
	-docker rm -vf $(ARGS) cosmohome_resultdata

create-data: create-projectdata create-resultdata
rm-data: rm-projectdata rm-resultdata

backup-projectdata:
	docker run --rm --volumes-from=cosmohome_projectdata cosmohome tar cvP /root/projects/cosmohome -O > backups/projectdata_$$(date -I).tar


#--- apache ---

build-apache:
	docker build -t cosmohome_apache apache

run-apache:
	docker run -p "80:80" -d -t \
			   --link cosmohome_mysql:mysql \
   			   --volumes-from=cosmohome_projectdata \
   			   --volumes-from=cosmohome_resultdata \
			   --hostname=cosmohome \
			   --name=cosmohome_apache \
			   -w /root/projects/cosmohome \
			   cosmohome_apache

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
	-docker rm -vf $(ARGS) cosmohome_mysqldata

run-mysql:
	docker run --volumes-from cosmohome_mysqldata \
			   --name cosmohome_mysql \
			   --env-file db_passwd.env \
			   -v $(PWD)/mysql:/etc/mysql/conf.d \
			   -d \
			   mysql:5.6.25
	# TODO: make work:
	# docker logs -f cosmohome_mysql 2>&1 | grep -m 1 "port: 3306  MySQL"
 
backup-mysqldata:
	docker run --rm --volumes-from=cosmohome_mysqldata cosmohome tar cvP /var/lib/mysql -O > backups/mysqldata_$$(date -I).tar


rm-mysql:
	-docker rm -f cosmohome_mysql

reset-mysql: rm-mysql rm-mysqldata create-mysqldata run-mysql


rm: rm-mysql rm-mysqldata rm-apache rm-projectdata rm-resultdata rm-cosmohome
