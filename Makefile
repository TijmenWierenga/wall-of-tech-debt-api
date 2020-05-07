PHP_VERSION = 7.4
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app php:${PHP_VERSION}-alpine

test: psalm

psalm:
	${DOCKER_RUN} vendor/bin/psalm --show-info=true