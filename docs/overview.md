Design Overview
---------------

The Cosmology@Home server is a [BOINC](https://github.com/BOINC/boinc) server running on a standard Linux-Apache-MySql-PHP stack which has been entirely containerized with Docker. 

Three separate containers do the job:

* `cosmohome_mysql` - An official Docker mysql image which runs the database storing users, hosts, jobs, etc...
* `cosmohome_cosmohome` - A debian container (built from [/Dockefile](/Dockefile)) which serves to build the project directory and initialize the database
* `cosmohome_apache` - A slightly modified official Docker Apache-PHP image (built from [/apache/Dockerfile](/apache/Dockerfile)) which takes the project directory which was previously built and actually runs the server. 

Three named volumes store server files, 

* `cosmohome_mysql` - Stores the database
* `cosmohome_project` - Stores the project directory
* `cosmohome_results` - Stores results returned from users

Creation and management of the containers is done with docker-compose and the configuration file at [/docker-compose.yml](/docker-compose.yml). There is also a [Makefile](/Makefile) which is just shorthand for some of the docker-compose commands. Building the server proceeds in the following way:

* `make run-mysql` to start the mysql container
* `make build-cosmohome` to build the `cosmohome_cosmohome` image, which will compile the BOINC source code, run BOINC's `./make_project` script to create the BOINC project folder structure, and copy in our various application files. 
* There are three things we need to do to fully build the server which we can't do in the previous step because Docker doesn't allow linking containers or mounting volumes during the build step,
    * We need to copy files into the `cosmohome_project` volume
    * We need to create the database (if it doesn't exist)
    * We need to update the database with any new applications we added (i.e. BOINC's `bin/update_versions` script)

    So there is a small script [cosmohome_postbuild.py](/cosmohome_postbuild.py) which does these things outside of the Docker build phase which can be run with `make postbuild-cosmohome`.
* Finally, with the database and project directory created, we simply build and run the Apache-PHP image with `make build-apache run-apache`. 

Thus the line,
```
make run-mysql build-apache build-cosmohome postbuild-cosmohome run-apache
```
will take you from zero to running server. With the server running, if you'd like a shell inside the Apache-PHP container to look at logs, perform various administrative actions, etc... you can run `make exec-apache`. 
