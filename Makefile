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

makeproject: 
	GITTAG="$$(git rev-parse --short HEAD),$$(TZ=UTC git show -s --format=%cd --date=local HEAD)" \
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
	docker exec -it $(shell $(DC) ps -q apache) bash


# --- mysql ---

build-mysql: 
	$(DC) build mysql

up-mysql:
	$(DC) up -d mysql

rm-mysql:
	$(DC) stop mysql && $(DC) rm -f mysql
