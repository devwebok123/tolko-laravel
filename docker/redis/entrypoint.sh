#!/usr/bin/env bash

cd /usr/local/etc/redis
cat ./redis.conf > ./redis.env.conf
sed -i s/@REDIS_PASSWORD@/${REDIS_PASSWORD}/ ./redis.env.conf
sed -i s/@REDIS_MAX_MEMORY@/${REDIS_MAX_MEMORY}/ ./redis.env.conf
redis-server ./redis.env.conf