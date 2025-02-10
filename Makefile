first: build run

run:
	docker compose up

build:
	docker compose build

fv: run 
