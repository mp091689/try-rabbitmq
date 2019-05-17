# How to start project

----

Go to dockerized folder and run docker-compose:

`$ cd PATH\TO\PROJECT\dockerized && docker-compose up`

Also we need to start rabbitmq consumer. To start it we need
to enter into the php container

`$ docker exec -it dockerized_php_1 su dev`

And inside the container run:

`bin/console rabbitmq:multiple-consumer contact`

----

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

----

## Used Technologies

* NGINX
* PHP 7.3
* MySQL 5.7
* Redis
* RabbitMQ
