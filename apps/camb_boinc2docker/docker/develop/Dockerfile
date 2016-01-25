FROM debian:wheezy

WORKDIR /root

RUN apt-get update && apt-get -y install gfortran wget make

#download CAMB
COPY camb /root/camb

WORKDIR /root/camb

#modify to compile with Gfortran
RUN echo "\n \
          F90C = gfortran \n\
          FFLAGS =  -O3 -fopenmp -ffast-math -funroll-loops \n\
          F90CRLINK =" >> Makefile

# stop -> error stop so we get correct exit codes if CAMB fails
RUN find . -name "*90" -exec sed -E -i 's/ stop / error stop /g' {} \;

RUN make

VOLUME "/release"

CMD ["cp","camb","HighLExtrapTemplate_lenspotentialCls.dat","/release"]
 
