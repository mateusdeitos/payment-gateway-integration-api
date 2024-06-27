up:
	docker compose -f .docker/docker-compose.yml up

bash:
	docker compose -f .docker/docker-compose.yml exec -it php bash

test:
	docker compose -f .docker/docker-compose.yml exec -it php bash -c "./vendor/bin/pest"
