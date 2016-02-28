default:

up: run-mysql build-cosmohome postbuild-cosmohome build-apache run-apache


DC=docker-compose
D=docker

download-private-data:
	git archive --remote=ssh://git@bitbucket.org/marius311/cosmohome_private.git --format=tar master | tar xvf -


# -- cosmohome ---

build-cosmohome: 
	mkdir -p keys
	$(DC) build cosmohome

postbuild-cosmohome:
	$(DC) run --rm cosmohome

#--- apache ---

build-apache:
	$(DC) build apache

run-apache:
	$(DC) up -d apache

rm-apache:
	$(DC) stop apache && $(DC) rm -f apache

exec-apache:
	$(D) exec -it cosmohome_apache bash



# --- mysql ---

run-mysql:
	$(DC) up -d mysql

rm-mysql:
	$(DC) stop mysql && $(DC) rm -f mysql


# --- backups ---

backup-mysql: 
	$(DC) stop mysql
	$(DC) run --rm mysql-backup
	$(DC) start mysql
	$(D) exec -it cosmohome_apache bin/start

backup-project: 
	$(DC) run --rm project-backup
