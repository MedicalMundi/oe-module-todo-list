# Makefile.openemr
#
# This file contains the custom commands and/or aliases.
#
# To use it, run make -f Makefile.openemr <command>.
# To see the command list run make -f Makefile.openemr help

#

# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

# make commands be run with `bash` instead of the default `sh`
SHELL='/bin/bash'

# .DEFAULT: If command does not exist in this makefile
# default:  If no command was specified:
.DEFAULT default:
	if [ "$@" != "" ]; then echo "Command '$@' not found."; fi;
	$(MAKE) help                        # goes to the main Makefile
	if [ "$@" != "" ]; then exit 2; fi;

help:
	@echo
	@echo "Available custom commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^help' | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

# stable available openemr release: v7_0_2|v7_0_1_1|v7_0_1|v7_0_0_2|v5_0_2|v5_0_2_1|v5_0_2_2|v5_0_2_3
# develop available openemr branch: master

ifndef OE_RELEASE_ENV
OE_RELEASE_ENV=v7_0_2
OE_DEVELOPMENT_ENV=development-easy
OE_DOCKER_COMPOSE_FILE=var/openemr-instance/${OE_RELEASE_ENV}/docker/${OE_DEVELOPMENT_ENV}/docker-compose.yml
endif

ifndef MODULE_ENV
MODULE_ENV=medicalmundi/oe-module-todo-list
endif

release-download:
	echo
	echo "Download Openemr:${OE_RELEASE_ENV}"
	git clone https://github.com/openemr/openemr.git -b ${OE_RELEASE_ENV} var/openemr-release/${OE_RELEASE_ENV}
	echo
	echo "Openemr:${OE_RELEASE_ENV} downloaded"

release-remove:
	echo "Remove release folder for Openemr:${OE_RELEASE_ENV}"
	rm -fR var/openemr-release/${OE_RELEASE_ENV}


instance-init:
	echo "Init Openemr instance version: ${OE_RELEASE_ENV}"
	rm -fR var/openemr-instance/${OE_RELEASE_ENV}/
	cp -fR var/openemr-release/${OE_RELEASE_ENV}/ var/openemr-instance/${OE_RELEASE_ENV}

instance-start:
	echo "Start Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} up -d
	$(MAKE) instance-fix-permission
	$(MAKE) instance-status
	$(MAKE) instance-log

instance-stop:
	echo "Stop Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} down

instance-status:
	echo "Check Openemr instance status: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} ps

instance-log:
	echo "Check Openemr instance status: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} logs -f openemr

instance-clean:
	echo "Stop and Clean Openemr instance version: ${OE_RELEASE_ENV}"
	$(MAKE) instance-stop
	echo "Clean Openemr volumes for instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} down -v

instance-fix-permission:
	echo "Fix file & folder permission in Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec -T openemr chown -R apache:apache /var/www/localhost/htdocs/openemr

instance-shell:
	echo "Entering in Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr sh

config-oe-instance:
	echo "Config Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec -T openemr php -f /var/www/localhost/htdocs/openemr/contrib/util/installScripts/InstallerAuto.php rootpass=root server=mysql loginhost=%

tail-error:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec -T openemr tail -f /var/log/apache2/error.log

tail-access:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec -T openemr tail -f /var/log/apache2/access.log

module-install:
	echo "Install ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer config repositories.medicalmundi/oe-module-todo-list vcs https://github.com/MedicalMundi/oe-module-todo-list.git --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer require ${MODULE_ENV}:^0.1 --prefer-source --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

module-dev-install:
	echo "Install ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer config repositories.medicalmundi/oe-module-todo-list vcs https://github.com/MedicalMundi/oe-module-todo-list.git --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer require ${MODULE_ENV}:dev-main --prefer-source --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

module-remove:
	echo "Remove ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer remove ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} removed in Openemr:${OE_RELEASE_ENV}"

module-update:
	echo "Update ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec openemr composer update ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} updated in Openemr:${OE_RELEASE_ENV}"