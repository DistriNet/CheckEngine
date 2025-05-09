FROM debian:stable-slim

RUN apt update -y && \
    apt install -y certbot

# Assgin UID to match nginx's
RUN useradd -u 1000 -s /bin/bash -m certbot && \
    mkdir -p /var/lib/letsencrypt && \
    mkdir -p /var/log/letsencrypt/ && \
    mkdir -p /etc/letsencrypt/ && \
    mkdir -p /var/www/certbot/ && \
    chown -R certbot:certbot /var/lib/letsencrypt && \
    chown -R certbot:certbot /var/log/letsencrypt/ && \
    chown -R certbot:certbot /etc/letsencrypt/ && \
    chown -R certbot:certbot /var/www/certbot/

USER certbot
ENTRYPOINT [ "certbot" ]
