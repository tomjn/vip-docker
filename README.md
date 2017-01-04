# VIP Docker

## Requirements

* Docker
* docker-compose

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
