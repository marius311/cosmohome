The Cosmology@Home server
-------------------------

Instructions:
```bash
make create-mysqldata run-mysql #wait ~10 sec for mysql server to start
make build-cosmohome create-data run-cosmohome CMD="./cosmohome_init.py"
make build-apache run-apache
```
