install: docker-build
docker-build: docker-composer docker-yarn
docker-composer:
	docker-compose run app composer --working-dir=/var/www install
docker-yarn:
	docker-compose run node yarn --cwd=/app install