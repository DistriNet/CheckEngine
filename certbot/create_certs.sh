#!/bin/bash
# Uncomment what's needed and run from project root.

# To create new certificates
# docker compose run --remove-orphans --rm certbot certonly -c /etc/letsencrypt/certbot.conf

# To renew certificates
docker compose run --remove-orphans --rm certbot renew -c /etc/letsencrypt/certbot.conf

# docker compose restart nginx
# docker compose exec nginx nginx -s reload
