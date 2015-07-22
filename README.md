The Cosmology@Home server
-------------------------

Instructions:
```bash
make create-mysqldata run-mysql #wait ~10 sec for mysql server to start
make build-cosmohome create-cosmohomedata run-cosmohome CMD="./cosmohome_init.py"
make run-apache
```
