## How to start project

Clone the project where you want:

`git clone git@github.com:mp091689/test.git test`

Go to dockerized folder and run docker-compose:

`$ cd test/dockerized && cp .env.example .env && CURRENT_UID=$(id -u):$(id -g) docker-compose up`

All needed dependencies will be installed automatically, be aware if you have first start the installation
can take time.

Next commands are should be executed as non root. Enter in to php container:

`$ docker exec -it dockerized_php_1 su dev`

Run migrations:

`$ bin/console doctrine:migrations:migrate`

## Usage

Configure /etc/hosts. Add to the end of hosts `127.0.0.1 app.local`.
Project should be accessible with [app.local:8080](http://app.local:8080)
or `localhost:8080` with out configurations.

Available routes:

`GET /api/contact` - get all records.

Response:

```json
[
    {  
        "uuid": "6f5e2c90-bf7b-4183-96df-fd5a162aff71",
        "firstName": "John",
        "lastName": "Snow",
        "phoneNumbers": [
            "812 123-1234",
            "916 123-4567"
        ]
    }
]
```

Filtering:
`$ GET /api/contact?filter=John`

Sorting:
`$ GET /api/contact?sort=c.firstName&direction=desc`

Pagination:
`$ GET /api/contact?page=1&limit=20`


`GET /contact/{uuid}` - get contact with specified uuid.

Response:

```json
{  
    "uuid": "6f5e2c90-bf7b-4183-96df-fd5a162aff71",
    "firstName": "John",
    "lastName": "Snow",
    "phoneNumbers": [
        "812 123-1234",
        "916 123-4567"
    ]
}
```

`POST /contact` - create new contact.

Expects JSON body:
```json
{  
    "firstName": "John",
    "lastName": "Snow",
    "phoneNumbers": [
        "812 123-1234",
        "916 123-4567"
    ]
}
```

Response:
```json
{
    "status": "OK",
    "message": "Sent to the queue. Entity uuid: 6f5e2c90-bf7b-4183-96df-fd5a162aff71"
}
```


`PUT /contact/{uuid}` - update contact with specified uuid.

Expects JSON body:
```json
{  
        "firstName": "Jaehaerys",
        "lastName": "Targaryen",
        "phoneNumbers": [
            "812 123-1234",
            "916 123-4567"
        ]
    }
```

`DELETE /contact/{uuid}` - delete contact with specified uuid.

PUT, DELETE requests are send one response:
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

RabbitMQ UI is accessible by link [localhost:15672](http://localhost:15672)

> login: mquser
>
> password : mqsecret

Consumers are will be started automatically.

Redis CLI is accessible with console command

```bash
$ docker exec -it dockerized_cache_1 redis-cli
```