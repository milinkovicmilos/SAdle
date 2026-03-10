FROM alpine:latest

RUN apk update && apk add \
    bash \
    mariadb-client

RUN echo "[client]" > /etc/my.cnf.d/skip-ssl.cnf
RUN echo "skip-ssl" >> /etc/my.cnf.d/skip-ssl.cnf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
