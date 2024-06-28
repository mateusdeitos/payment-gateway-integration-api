# Running the application

## Requirements

- docker cli installed
- `.env.local` file created accordingly to the `.env` file

## Commands

A makefile was created to facilitate running the application.

- `make up`: will start the docker container and install any missing dependencies, the server will be running on the port `9001`
- `make bash`: access the bash of the `php` service (the php service must be running)
- `make test`: execute the unit tests (the php service must be running)
- `app:create-payment`: command available to create a payment in one of the providers (available only inside the php service after running `make bash`)

## Debugging

- Xdebug will be enabled by default in the port `9003`
- To list the application routes, run `make bash` to enter the container and then run `sf debug:router`
