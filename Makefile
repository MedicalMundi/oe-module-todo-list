#
# This file contains the custom commands and/or aliases.
#
# To use it, run make <command>.
# To see the command list run make help

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
#OE_DOCKER_COMPOSE_FILE=./../openemr-instance/${OE_RELEASE_ENV}/docker/${OE_DEVELOPMENT_ENV}/docker-compose.yml -f docker-compose-module.yml
endif

ifndef MODULE_ENV
VENDOR_NAME_ENV=medicalmundi
PACKAGE_NAME_ENV=oe-module-todo-list
MODULE_ENV=${VENDOR_NAME_ENV}/${PACKAGE_NAME_ENV}
endif

ifndef CURRENT_INSTANCE_ENV
CURRENT_INSTANCE_ENV=${PACKAGE_NAME_ENV}-${OE_RELEASE_ENV}
endif

ifndef OE_DOCKER_COMPOSE_FILE
OE_DOCKER_COMPOSE_FILE=./../openemr-instance/${CURRENT_INSTANCE_ENV}/docker/${OE_DEVELOPMENT_ENV}/docker-compose.yml -f docker-compose-module.yml
endif

release-download:
	echo
	echo "Download Openemr:${OE_RELEASE_ENV}"
	git clone https://github.com/openemr/openemr.git -b ${OE_RELEASE_ENV} ./../openemr-release/${OE_RELEASE_ENV}
	echo
	echo "Openemr:${OE_RELEASE_ENV} downloaded"

release-remove:
	echo "Remove release folder for Openemr:${OE_RELEASE_ENV}"
	rm -fR var/openemr-release/${OE_RELEASE_ENV}


instance-init:
	echo "Init Openemr instance version: ${OE_RELEASE_ENV}"
	rm -fR ./../openemr-instance/${OE_RELEASE_ENV}/
	cp -fR ./../openemr-release/${OE_RELEASE_ENV}/ ./../openemr-instance/${CURRENT_INSTANCE_ENV}

instance-start:
	echo "Start Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} up -d
	#$(MAKE) instance-fix-permission
	$(MAKE) instance-status
	$(MAKE) instance-log

instance-stop:
	echo "Stop Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} down

instance-status:
	echo "Check Openemr instance status: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} ps

instance-log:
	echo "Check Openemr instance status: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} logs -f openemr

instance-clean:
	echo "Stop and Clean Openemr instance version: ${OE_RELEASE_ENV}"
	$(MAKE) instance-stop
	echo "Clean Openemr volumes for instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} down -v

instance-fix-permission:
	echo "Fix file & folder permission in Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec -T openemr chown -R apache:apache /var/www/localhost/htdocs/openemr
	#docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec -T openemr chown -R apache:apache /${PACKAGE_NAME_ENV}
	#docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec -T openemr chown -R apache:apache /${PACKAGE_NAME_ENV} --IGNORE-ME--

instance-shell:
	echo "Entering in Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr sh

config-oe-instance:
	echo "Config Openemr instance version: ${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} exec -T openemr php -f /var/www/localhost/htdocs/openemr/contrib/util/installScripts/InstallerAuto.php rootpass=root server=mysql loginhost=%

tail-error:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec -T openemr tail -f /var/log/apache2/error.log

tail-access:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec -T openemr tail -f /var/log/apache2/access.log


#
#	MODULE SOURCE: PACKAGIST.ORG
# 	MODULE OPERATIONS INSIDE DOCKER
#

module-install:
	echo "Install ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer require ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

module-dev-install:
	echo "Install ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer require ${MODULE_ENV}:dev-main --prefer-source --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

module-remove:
	echo "Remove ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer remove ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} removed in Openemr:${OE_RELEASE_ENV}"

module-update:
	echo "Update ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer update ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	$(MAKE) instance-fix-permission
	echo "Module ${MODULE_ENV} updated in Openemr:${OE_RELEASE_ENV}"


#
#	MODULE SOURCE: MOUNTED LOCAL FOLDER IN DOCKER
# 	MODULE OPERATIONS INSIDE DOCKER
#

local-module-install:
	echo "Install ${MODULE_ENV} as local module (from directory) in openemr:${OE_RELEASE_ENV}"
	$(MAKE) composer-fix-issue-psr-cache
	$(MAKE) composer-add-merge-plugin
	$(MAKE) composer-configure-merge-plugin
	$(MAKE) composer-trigger-module-deps
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

local-module-remove:
	echo "Remove ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	$(MAKE) composer-remove-merge-plugin
	$(MAKE) composer-unconfigure-merge-plugin
	$(MAKE) composer-trigger-module-deps
	echo "Module ${MODULE_ENV} removed in Openemr:${OE_RELEASE_ENV}"


#
#	MODULE SOURCE: GITHUB.COM
# 	MODULE OPERATIONS INSIDE DOCKER
#

github-module-install:
	echo "Install ${MODULE_ENV} from github.com in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config repositories.${MODULE_ENV} vcs https://github.com/${MODULE_ENV}.git --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer require ${MODULE_ENV}:dev-main --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

github-module-dev-install:
	echo "Install ${MODULE_ENV} from github.com in openemr:${OE_RELEASE_ENV}"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config repositories.${MODULE_ENV} vcs https://github.com/${MODULE_ENV}.git --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer require ${MODULE_ENV}:dev-main --prefer-source --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} installed in Openemr:${OE_RELEASE_ENV}"

github-module-remove:
	echo "Remove ${MODULE_ENV} in openemr:${OE_RELEASE_ENV}"
	echo "Remove composer repositories.${OE_RELEASE_ENV} reference in composer.json"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer remove ${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config --unset repositories.${MODULE_ENV} --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} removed in Openemr:${OE_RELEASE_ENV}"



composer-fix-issue-psr-cache:
	echo "Fix composer issue psr/cache in openemr:${OE_RELEASE_ENV} composer.json"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer update psr/cache:2.0.0 --working-dir /var/www/localhost/htdocs/openemr
	echo "Module ${MODULE_ENV} removed in Openemr:${OE_RELEASE_ENV}"


composer-add-merge-plugin:
	echo "Install wikimedia/composer-merge-plugin in openemr:${OE_RELEASE_ENV} composer.json"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config --no-interaction allow-plugins.wikimedia/composer-merge-plugin true --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer require wikimedia/composer-merge-plugin --working-dir /var/www/localhost/htdocs/openemr
	echo "wikimedia/composer-merge-plugin installed in Openemr:${OE_RELEASE_ENV}"

composer-remove-merge-plugin:
	echo "Remove wikimedia/composer-merge-plugin in openemr:${OE_RELEASE_ENV} composer.json"
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer remove wikimedia/composer-merge-plugin --working-dir /var/www/localhost/htdocs/openemr
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config --unset --no-interaction allow-plugins.wikimedia/composer-merge-plugin --working-dir /var/www/localhost/htdocs/openemr
	echo "wikimedia/composer-merge-plugin removed from Openemr:${OE_RELEASE_ENV}"


composer-configure-merge-plugin:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config --json extra.merge-plugin '{"include": "/var/www/localhost/htdocs/openemr/interface/modules/custom_modules/${PACKAGE_NAME_ENV}/composer.json", "merge-dev": false, "merge-scripts": false }' --working-dir /var/www/localhost/htdocs/openemr

composer-unconfigure-merge-plugin:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer config --unset extra.merge-plugin --working-dir /var/www/localhost/htdocs/openemr

composer-trigger-module-deps:
	docker-compose -f ${OE_DOCKER_COMPOSE_FILE} -p ${CURRENT_INSTANCE_ENV} exec openemr composer update psr/cache:2.0.0 --working-dir /var/www/localhost/htdocs/openemr
