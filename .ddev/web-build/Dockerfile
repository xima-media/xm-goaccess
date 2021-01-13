
# You can copy this Dockerfile.example to Dockerfile to add configuration
# or packages or anything else to your webimage
ARG BASE_IMAGE
FROM $BASE_IMAGE

#
# Install pip for https://github.com/jackd248/db-sync-tool
#
RUN apt-get update \
 && apt-get install -y python3-pip
