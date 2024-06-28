# Running the application

## Requirements

- docker cli installed
- `.env.local` file created accordingly to the `.env` file

## Commands

A makefile was created to facilitate running the application.

- `make up`: will start the docker container and install any missing dependencies, the server will be running in the port `9001`
- `make bash`: access the bash of the server service (the container must be running)
- `make test`: execute the unit tests
- `app:create-payment`: command available to create a payment in one of the providers (available only inside the container after running `make bash`)

## Debugging

- Xdebug will be enabled by default in the port `9003`
- To list the application routes, run `make bash` to enter the container and then run `sf debug:router`
