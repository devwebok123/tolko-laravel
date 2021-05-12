FROM redis:5.0.1-alpine

ADD config/redis.conf /usr/local/etc/redis/redis.conf
ADD entrypoint.sh /entrypoint.sh

ENTRYPOINT /bin/sh -c "/bin/sh /entrypoint.sh"