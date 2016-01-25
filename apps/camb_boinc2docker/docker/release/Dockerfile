FROM debian:wheezy

WORKDIR /root

RUN apt-get update && \
    apt-get -y install libgfortran3 libgomp1 && \
    rm -rf /var/lib/apt/lists/*

COPY ["camb", "HighLExtrapTemplate_lenspotentialCls.dat", "/root/"]

ENTRYPOINT ["./camb"]
