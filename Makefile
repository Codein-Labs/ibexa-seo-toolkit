.SILENT:

DOCKER_NAME     = ezplatformseotoolkit25_engine_1
DOCKER_APP_DIR  = /var/www/html/project/package/codein/ezplatform-seo-toolkit
DOCKER-EXEC     = docker exec --tty=true --user=1000 -w $(DOCKER_APP_DIR) -i $(DOCKER_NAME)
test: ## Launch all functionnal and unit tests
test: phpunit.xml
	- echo '###### <-- Runing functionnal and unit tests --> ######'
	- $(DOCKER-EXEC) composer test

php-cs-fixer: ## Run php-cs-fixer
php-cs-fixer:
	- echo '###### <-- Runing php-cs-fixer --> ######'
	- $(DOCKER-EXEC) vendor/bin/php-cs-fixer fix --config=./php_cs_fixer.dist --verbose

psalm: ## Run PSALM
psalm:
	- $(DOCKER-EXEC) vendor/bin/psalm
