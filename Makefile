default:

DC=docker-compose

up: up-mysql post-makeproject up-apache

down: 
	$(DC) down
build: 
	$(DC) build

pull: 
	$(DC) pull


#--- project ---

GITTAG=$(shell git rev-parse --short HEAD),$(shell TZ=UTC git show -s --format=%cd --date=local HEAD)
makeproject: 
	$(DC) build makeproject

post-makeproject:
	$(DC) run --rm makeproject

#--- apache ---

build-apache:
	$(DC) build apache

up-apache:
	$(DC) up -d apache

rm-apache:
	$(DC) stop apache && $(DC) rm -f apache

exec-apache:
	docker exec -it cosmohome_apache bash


# --- mysql ---

build-mysql: 
	$(DC) build mysql

up-mysql:
	$(DC) up -d mysql

rm-mysql:
	$(DC) stop mysql && $(DC) rm -f mysql


# --- backups ---

backup-mysql: 
	$(DC) stop mysql
	$(DC) run --rm mysql-backup
	$(DC) start mysql
	$(DC) restart apache

backup-project: 
	$(DC) run --rm project-backup
