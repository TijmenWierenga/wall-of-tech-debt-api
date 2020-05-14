PHP_VERSION = 7.4
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app php:${PHP_VERSION}-alpine
IMAGE_NAME = wall-of-tech-debt/api

build_dev:
	DOCKER_BUILDKIT=1 docker build -t ${IMAGE_NAME}:dev .

start:
	docker run -itd --rm --name=wall-api -v $$(pwd):/var/www/html -p 80:80 ${IMAGE_NAME}:dev

stop:
	docker kill wall-api

test: phpcs psalm phpunit

phpcs:
	${DOCKER_RUN} vendor/bin/phpcs

psalm:
	${DOCKER_RUN} vendor/bin/psalm --show-info=true

phpunit:
	docker run -it --rm -v $$(pwd):/var/www/html -u $$(id -u):$$(id -g) ${IMAGE_NAME}:dev bin/phpunit