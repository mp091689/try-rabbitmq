## How to start project

Go to dockerized folder and run docker-compose:

`$ cd PATH/TO/PROJECT/dockerized && cp .env.example .env && docker-compose up`

Also we need to install dependencies using composer 
and start rabbitmq consumer. To start it we need
to enter into the php container

`$ docker exec -it dockerized_php_1 su dev`

Inside the container run commands:

`$ composer install`

`$ bin/console rabbitmq:multiple-consumer contact`

## Usage

Project should be accessible with `localhost:8080`
or `app.local:8080` if /etc/hosts are configured.

Available routes:

`GET /contact` - get all records.

Response:

```json
[
  {
    "id":1,
    "firstName":"Name"
  },
  ...
]
```

`GET /contact/{id}` - get contact with specified id.

Response:

```json
{
  "id":1,
  "firstName":"Name"
}
```

`POST /contact` - create new contact.

Expects JSON body:
```json
{
  "firstName": "Name"
}
```

`PUT /contact/{id}` - update contact with specified id.

Expects JSON body:
```json
{
  "firstName": "NewName"
}
```

`DELETE /contact/{id}` - delete contact with specified id.

POST, PUT, DELETE requests are send one response:
```json
{
  "status":"OK",
  "message":"Sent to the queue"
}
```

All validation and other error are logged in `app/var/log/contact.log`

## Used Technologies

* NGINX
* PHP 7.3
* MySQL 5.7
* Redis
* RabbitMQ

## Additional

RabbitMQ UI is accessible by link http://localhost:15672

> login: mquser
>
> password : mqsecret

Redis CLI is accessible with console command

```bash
$ docker exec -it dockerized_cache_1 redis-cli
```