USER_ID := $(shell id -u)
GROUP_ID := $(shell id -g)
PHP_IMAGE:=php:8.1.33-cli
COMPOSER_IMAGE:=composer/composer:2.8.12

############## Docker Targets ##############

install-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" -u ${USER_ID}:${GROUP_ID} ${COMPOSER_IMAGE} composer install

audit-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${COMPOSER_IMAGE} composer audit

phpstan-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${PHP_IMAGE} vendor/bin/phpstan analyse -c phpstan.neon

rector-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${PHP_IMAGE} vendor/bin/rector process

rector-dry-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${PHP_IMAGE} vendor/bin/rector process --dry-run

csfix-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" -u ${USER_ID}:${GROUP_ID} ${PHP_IMAGE} vendor/bin/php-cs-fixer fix --allow-risky=yes

csfix-dry-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${PHP_IMAGE} vendor/bin/php-cs-fixer fix --dry-run --allow-risky=yes

phpunit-docker:
	docker run --rm -w "/php-library" -v "./:/php-library" ${PHP_IMAGE} vendor/bin/phpunit

############## Github Actions Targets (also for bare-metal php usage) ##############

audit:
	composer audit

csfix:
	vendor/bin/php-cs-fixer fix

csfix-dry:
	vendor/bin/php-cs-fixer fix --dry-run --allow-risky=yes

rector:
	vendor/bin/rector process

rector-dry:
	vendor/bin/rector process --dry-run

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

phpunit:
	vendor/bin/phpunit
