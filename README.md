
![banner](html/user/img/banner.jpg)

The Cosmology@Home server
=========================

The [Cosmology@Home](http://cosmologyathome.org) server is a multi-container [Docker](http://www.docker.com) application. In a few commands anyone can check out the code from here and have a local version of the server running (identical to the real thing in every way except for user data and a few private files). 

The requirements for running the server are:
* [docker](http://docs.docker.com/engine/installation/)
* [docker-compose](https://docs.docker.com/compose/install/)
* [make](https://www.gnu.org/software/make/)

To download, build, and start the server on a fresh environment:

```bash
git clone --recursive https://github.com/marius311/cosmohome.git
cd cosmohome
make run-mysql build-apache build-cosmohome postbuild-cosmohome run-apache
```

*Note: the first time you run this it may take a while as many dependencies are downloaded and images are built from scratch.*

At this point, you should be able to connect your browser to [localhost](http://localhost:80) to see the server webpage. To connect a BOINC client to the server, you need to reroute [www.cosmologyathome.org](http://www.cosmologyathome.org) to [localhost](http://localhost:80) (b/c the server code has it's URL hardcoded). On Linux, this can be done by adding the line `127.0.0.1 www.cosmologyathome.org` to your `/etc/hosts` file. Then connect BOINC to [www.cosmologyathome.org](http://www.cosmologyathome.org) as usual. 
