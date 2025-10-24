install:
	docker run --rm -w "/sdk" -v "./:/sdk" composer/composer:2.8.12 composer install

audit:
	docker run --rm -w "/sdk" -v "./:/sdk" composer/composer:2.8.12 composer audit

phpstan:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/phpstan analyse -c phpstan.neon

rector:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/rector process --dry-run

csfix:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/php-cs-fixer fix --dry-run

phpunit:
	docker run --rm -w "/sdk" -v "./:/sdk" php:8.1.33-cli vendor/bin/phpunit