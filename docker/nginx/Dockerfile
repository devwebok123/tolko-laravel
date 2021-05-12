FROM nginx:1.15.5-alpine

COPY config/ /
ADD entrypoint.sh /entrypoint.sh

ENTRYPOINT /bin/sh -c "/bin/sh /entrypoint.sh"