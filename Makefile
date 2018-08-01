default:

DC=docker-compose
DCA=$(DC) -f docker-compose.yml -f docker-compose-admin.yml 

up: up-mysql post-makeproject up-apache

down: 
	$(DC) down

build: makeproject build-mysql build-apache

pull: 
	$(DC) pull


#--- project ---

makeproject: 
	mkdir -p secrets
	GITTAG="$$(git rev-parse --short HEAD),$$(TZ=UTC git show -s --format=%cd --date=local HEAD)" \
	$(DC) build makeproject

post-makeproject:
	$(DC) run --rm makeproject

backup-project: 
	$(DCA) run --rm backup-project



#--- apache ---

build-apache:
	$(DC) build apache

up-apache:
	$(DC) up -d apache

rm-apache:
	$(DC) stop apache && $(DC) rm -f apache

exec-apache:
	$(DC) exec -u boincadm apache bash


# --- mysql ---

build-mysql: 
	$(DC) build mysql

up-mysql:
	$(DC) up -d mysql

rm-mysql:
	$(DC) stop mysql && $(DC) rm -f mysql

optimize-mysql: 
	$(DC) exec apache bin/stop
	$(DC) run --rm mysql mysqlcheck -h mysql -o cosmohome
	$(DC) exec apache bin/start

backup-mysql: 
	$(DC) stop mysql
	$(DCA) run --rm backup-mysql
	$(DC) start mysql
	$(DC) restart apache
