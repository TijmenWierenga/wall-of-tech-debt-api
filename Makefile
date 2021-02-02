PHP_VERSION = 7.4
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app php:${PHP_VERSION}-alpine
IMAGE_NAME = wall-of-tech-debt/api

build_dev:
	DOCKER_BUILDKIT=1 docker build -t ${IMAGE_NAME}:dev .

serve_docs:
	docker run -itd --rm -v $$(pwd)/openapi.v1.yaml:/usr/share/nginx/html/openapi.yaml -e SPEC_URL=/openapi.yaml -p 8080:80 redocly/redoc

start:
	docker run -itd --rm --name=wall-api -v $$(pwd):/var/www/html -p 80:80 ${IMAGE_NAME}:dev

stop:
	docker kill wall-api

test: phpcs psalm deptrac phpunit infection

phpcs:
	${DOCKER_RUN} vendor/bin/phpcs

psalm:
	${DOCKER_RUN} vendor/bin/psalm

phpunit:
	docker run -it --rm -v $$(pwd):/var/www/html -u $$(id -u):$$(id -g) ${IMAGE_NAME}:dev bin/phpunit

infection:
	docker run -it --rm -v $$(pwd):/var/www/html -u $$(id -u):$$(id -g) ${IMAGE_NAME}:dev phpdbg -qrr 'vendor/bin/infection' --threads=4

deptrac:
	${DOCKER_RUN} vendor/bin/deptrac

phpcbf:
	${DOCKER_RUN} vendor/bin/phpcbf