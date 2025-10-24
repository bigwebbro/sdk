USER_ID := $(shell id -u)
GROUP_ID := $(shell id -g)

install-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" -u ${USER_ID}:${GROUP_ID} composer/composer:2.8.12 composer install

audit-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" composer/composer:2.8.12 composer audit

phpstan-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/phpstan analyse -c phpstan.neon

rector-dry-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/rector process --dry-run

csfix-dry-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/php-cs-fixer fix --dry-run

csfix-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" -u ${USER_ID}:${GROUP_ID} php:8.1.33-cli vendor/bin/php-cs-fixer fix

phpunit-docker:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/phpunit


############## Github Actions Targets ##############
audit:
	composer audit

csfix-dry:
	vendor/bin/php-cs-fixer fix --dry-run

rector-dry:
	vendor/bin/rector process --dry-run

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon

phpunit:
	vendor/bin/phpunit
