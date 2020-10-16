.SILENT:

DOCKER_NAME     = ezplatformseotoolkit25_engine_1
DOCKER_APP_DIR  = /var/www/html/project/package/codein/ezplatform-seo-toolkit
DOCKER-EXEC     = docker exec --tty=true --user=1000 -w $(DOCKER_APP_DIR) -i $(DOCKER_NAME)
test: ## Launch all functionnal and unit tests
test: phpunit.xml
	- echo '###### <-- Runing functionnal and unit tests --> ######'
	- $(DOCKER-EXEC) vendor/bin/simple-phpunit --stop-on-failure --debug
