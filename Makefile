
all: deps test phar

deps:
	./composer.phar install --no-interaction

deps_prod:
	./composer.phar install --no-dev --no-interaction

ci_test: clean deps phpunit
ci_phar: clean deps_prod phar

test: phpunit

phpunit:
	./vendor/bin/phpunit

phar:
	rm -f sendgrid.phar
	php tasks/build-phar.php

clean:
	rm -rf vendor
	rm -f sendgrid.phar
