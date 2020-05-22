DOCKER_COMPOSE = docker-compose
PROJECT = "Studoc."
COMPOSER ?= composer
PHP_CMD = php
PHP_SERVICE = php-fpm
COMPOSE_PROJECT_NAME ?= $(notdir $(shell pwd))

ifeq ($(APP_ENV), prod)
	CMD :=
else
	CMD := docker-compose exec $(PHP_SERVICE)
endif

ifeq ($(COMPOSE_PROJECT_NAME), )
  $(error Variable `COMPOSE_PROJECT_NAME` is not set, exiting...)
endif

help:
	@ echo "Makefile for project.\n"
	@ echo "Usage: make <target>\n"
	@ echo "Available targets:\n"
	@ cat Makefile | grep -oE "^[^: ]+:" | grep -oE "[^:]+" | grep -Ev "help|default|.PHONY"

all: container-up lint-composer lint-php lint-json lint-eol composer-install phpcs tests

container-stop:
	@echo "\n==> Stopping docker container"
	$(DOCKER_COMPOSE) stop

container-down:
	@echo "\n==> Removing docker container"
	$(DOCKER_COMPOSE) down

container-remove:
	@echo "\n==> Removing docker container(s)"
	$(DOCKER_COMPOSE) rm

container-up:
	@echo "\n==> Docker container building and starting ..."
	$(DOCKER_COMPOSE) up --build -d

tear-down: container-stop container-down container-remove

tests:
	@echo "\n==> Running tests"
	$(CMD) bin/phpunit

coverage:
	@echo "\n==> Generating coverage report"
	$(CMD) bin/phpunit --coverage-html coverage

lint: lint-json lint-yaml lint-php phpcs lint-composer lint-eol
	@echo All good.

lint-eol:
	@echo "\n==> Validating unix style line endings of files:"
	@! grep -lIUr --color '^M' app/ web/ src/ composer.* || ( echo '[ERROR] Above files have CRLF line endings' && exit 1 )
	@echo All files have valid line endings

lint-composer:
	@echo "\n==> Validating composer.json and composer.lock:"
	$(CMD) $(COMPOSER) validate --strict

lint-json:
	@echo "\n==> Validating all json files:"
	@find src -type f -name \*.json -o -name \*.schema | php -R 'echo "$$argn\t\t";json_decode(file_get_contents($$argn));if(json_last_error()!==0){echo "<-- invalid\n";exit(1);}else{echo "\n";}'

lint-yaml:
	@echo "\n==> Validating all yaml files:"
	@find app/config src -type f -name \*.yml | while read file; do echo -n "$$file"; $(CMD) php bin/console --no-debug --no-interaction --env=test lint:yaml "$$file" || exit 1; done

lint-php:
	@echo "\n==> Validating all php files:"
	$(CMD) find src tests -type f -iname '*php' -exec $(PHP_CMD) -l {} \;

phpcs:
	@echo "\n==> Checking php styles"
	$(CMD) vendor/bin/phpcs --standard=phpcs.xml -p

phpcbf:
	@echo "\n==> Fixing styling errors"
	$(CMD) vendor/bin/phpcbf

composer-install:
	@echo "\n==> Running composer install, runner $(RUNNER)"
	$(CMD) $(COMPOSER) install

.PHONY: container-up container-stop container-down tear-down all composer-install cc lint lint-eol lint-composer lint-json lint-yaml lint-php phpcs phpcbf tests coverage
