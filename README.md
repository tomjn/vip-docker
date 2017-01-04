# VIP Docker

## Requirements

* git
* svn
* Docker
* docker-compose

## Usage

Start:

```
bin/up
```

Although it's not recommended, you can alternatively use:

```
docker-compose up -d
```

Stop:

```
docker-compose down
```

## Updates

1. `bin/up`

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

## FAQ

#### Can I maintain the Database between updates?

Yes! Add the following to the mysql section in `docker-custom.yml`

```
mysql:
	volumes:
		- ".data:/var/lib/mysql"
```
