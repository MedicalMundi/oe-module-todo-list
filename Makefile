ifndef BUILD_ENV
BUILD_ENV=7.2
endif

ifndef DOCQA_DOCKER_COMMAND
DOCQA_DOCKER_IMAGE=dkarlovi/docqa:latest
DOCQA_DOCKER_COMMAND=docker run --init --interactive --rm -t --user "$(shell id -u):$(shell id -g)"  --volume "$(shell pwd)/var/tmp/docqa:/.cache" --volume "$(shell pwd):/project" --workdir /project ${DOCQA_DOCKER_IMAGE}
endif

ifndef PHPQA_DOCKER_COMMAND
PHPQA_DOCKER_IMAGE=jakzal/phpqa:1.35-php${BUILD_ENV}-alpine
PHPQA_DOCKER_COMMAND=docker run --init --interactive --rm -t --env "COMPOSER_CACHE_DIR=/composer/cache" --user "$(shell id -u):$(shell id -g)" --volume "$(shell pwd)/var/tmp/phpqa:/tmp" --volume "$(shell pwd):/project" --volume "${HOME}/.composer:/composer" --workdir /project ${PHPQA_DOCKER_IMAGE}
endif

dist: composer-normalize cs phpstan psalm test doc
check: composer-validate cs-check analyze
analyze: phpstan psalm
ci-analyze: ci-phpstan ci-psalm
ci-check: composer-validate ci-cs-check ci-analyze
test: infection
ci-test: ci-infection
doc: markdownlint textlint proselint vale

composer-validate: ensure composer-normalize-check
	sh -c "${PHPQA_DOCKER_COMMAND} composer validate"
composer-install: fetch ensure
	sh -c "${PHPQA_DOCKER_COMMAND} composer upgrade"
composer-bare-install:
	sh -c "${PHPQA_DOCKER_COMMAND} composer upgrade"
composer-install-lowest: fetch ensure
	sh -c "${PHPQA_DOCKER_COMMAND} composer upgrade --with-all-dependencies --prefer-lowest"
composer-bare-install-lowest:
	sh -c "${PHPQA_DOCKER_COMMAND} composer upgrade --with-all-dependencies --prefer-lowest"
composer-normalize: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} composer normalize"
composer-normalize-check: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} composer normalize --dry-run"

cs: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} php-cs-fixer fix --using-cache=false --diff -vvv"
cs-check: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} php-cs-fixer fix --using-cache=false --dry-run --diff -vvv"
ci-cs-check: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} php-cs-fixer fix --using-cache=false --dry-run --diff -vvv --format=checkstyle | vendor/bin/cs2pr"

phpstan: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} phpstan analyse"
ci-phpstan: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} phpstan analyse --error-format=checkstyle | vendor/bin/cs2pr"

psalm: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} psalm --show-info=false --threads max"
ci-psalm: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} psalm --show-info=false --threads max --output-format=checkstyle | vendor/bin/cs2pr"

phpunit:
	sh -c "${PHPQA_DOCKER_COMMAND} vendor/bin/phpunit --verbose"
phpunit-coverage: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} php -d pcov.enabled=1 vendor/bin/phpunit --verbose --coverage-text --log-junit=var/junit.xml --coverage-xml var/coverage-xml/"
ci-phpunit-coverage: ensure
	sh -c "${PHPQA_DOCKER_COMMAND} php -d pcov.enabled=1 vendor/bin/phpunit --printer \"mheap\\GithubActionsReporter\\Printer\" --log-junit=var/junit.xml --coverage-xml var/coverage-xml/"

infection: phpunit-coverage
	sh -c "${PHPQA_DOCKER_COMMAND} infection run --verbose --show-mutations --no-interaction --only-covered --coverage var/ --min-msi=100 --min-covered-msi=100 --threads 4"
ci-infection: ci-phpunit-coverage
	sh -c "${PHPQA_DOCKER_COMMAND} infection run --verbose --show-mutations --no-interaction --only-covered --coverage var/ --min-msi=100 --min-covered-msi=100 --threads 4"

markdownlint: ensure
	sh -c "${DOCQA_DOCKER_COMMAND} markdownlint *.md docs/"
proselint: ensure
	sh -c "${DOCQA_DOCKER_COMMAND} proselint README.md docs/"
textlint: ensure
	sh -c "${DOCQA_DOCKER_COMMAND} textlint -c docs/.textlintrc.dist README.md"
vale: ensure
	sh -c "${DOCQA_DOCKER_COMMAND} vale --config docs/.vale.ini.dist README.md"

ensure:
	mkdir -p ${HOME}/.composer var/tmp/docqa var/tmp/phpqa
fetch:
	docker pull "${DOCQA_DOCKER_IMAGE}"
	docker pull "${PHPQA_DOCKER_IMAGE}"
clean:
	rm -rf var/cache/