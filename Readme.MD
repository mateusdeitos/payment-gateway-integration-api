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

## Testing the integrations

### Shift4

 - Create a dev account and generate an api key
 > for a list of card numbers to use for testing purposes: https://dev.shift4.com/docs/testing

example request:
```curl
curl --request POST \
  --url http://localhost:9001/api/v1/shift4/payment \
  --header 'Content-Type: application/json' \
  --data '{
  "amount": 400,
  "currency": "USD",
  "cardNumber": "4012000100000007",
  "cardExpMonth": 12,
  "cardExpYear": 2032,
  "cardCvv": 123
}'
```

### ACI

 - Documentation: https://docs.oppwa.com/integrations/server-to-server#syncPayment

example request:

```curl

curl --request POST \
  --url http://localhost:9001/api/v1/aci/payment \
  --header 'Content-Type: application/json' \
  --data '{
  "amount": 400,
  "currency": "EUR",
  "cardNumber": "4012000100000007",
  "cardExpMonth": 12,
  "cardExpYear": 2032,
  "cardCvv": 123
}'
```
