FROM marius311/boincserver_apache:latest

MAINTAINER Marius Millea <mariusmillea@gmail.com>

#install packages 
RUN apt-get update && apt-get install -y \
        python-matplotlib \
        python-mysqldb \
        python-numpy \
        python-scipy \
        python-yaml \
    && curl -L http://antiyawn.com/uploads/Humor-Sans-1.0.ttf > /usr/share/matplotlib/mpl-data/fonts/ttf/Humor-Sans.ttf
