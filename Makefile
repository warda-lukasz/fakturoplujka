first: build run

run:
	docker compose up

build:
	docker compose build

copy-files:
	./copyOutput.sh

fv: run copy-files
