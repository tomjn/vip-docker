# VIP Docker

## Requirements

* Docker
* docker-compose

## Usage

Start:

```
docker-compose up -d
```

Stop:

```
docker-compose down
```

## Run WP-CLI Commands

1. `docker ps`
1. Get the ID of the PHP container
1. `docker exec -it <id> <command>`

## Deploying

To deploy to a public environment, define the following environment
variables in `docker-custom.yml` and run `docker-compose -f docker-compose.yml -f docker-production.yml -f docker-custom.yml up -d`.

```
php:
    environment:
        WP_DOMAIN: example.com
        WP_ADMIN_USER: me
        WP_EMAIL: me@example.com
```
