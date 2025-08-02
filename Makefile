include .env

build:
	make check-dotenv;
	docker compose -f docker-compose.yml build

up:
	make check-dotenv;
	docker compose -f docker-compose.yml up -d

down:
	make check-dotenv;
	docker compose -f docker-compose.yml down

bl: build
ul: up
dl: down

check-dotenv:
	@test -e sandbox/.env || (echo "[ ERROR ] - .env file not found!"; exit 1)
