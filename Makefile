
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
	-docker rm -vf $(ARGS) cosmohomedata

reset-cosmohome: rm-cosmohomedata create-cosmohomedata run-cosmohome

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
 
rm-mysql:
	-docker rm -f cosmohome_mysql

reset-mysql: rm-mysql rm-mysqldata create-mysqldata run-mysql


rm: rm-mysql rm-mysqldata rm-apache rm-cosmohomedata rm-cosmohome
