#!/usr/bin/make
include .env
export $(shell sed 's/=.*//' .env)
compose=docker-compose -f docker-compose.yml

.DEFAULT_GOAL := help

.PHONY: wakeapp
wakeapp:
	$(compose) exec php-fpm bin/console d:da:dr --force
	$(compose) exec php-fpm bin/console d:da:cr
	$(compose) exec php-fpm bin/console d:m:m -n
	$(compose) exec php-fpm bin/console d:f:l -n

.PHONY: stan
stan:
	$(compose) exec php-fpm ./vendor/bin/phpstan

.PHONY: stan-b
stan-b:
	$(compose) exec php-fpm ./vendor/bin/phpstan -b

.PHONY: cs
cs:
	$(compose) exec php-fpm ./vendor/bin/php-cs-fixer fix

.PHONY: rector
rector:
	$(compose) exec php-fpm ./vendor/bin/rector

.PHONY: cmpsrnrmlz
cmpsrnrmlz:
	$(compose) exec php-fpm  ./vendor/bin/composer-require-checker check composer.json
	$(compose) exec php-fpm composer normalize

.PHONY: anlz
anlz: cs rector stan
