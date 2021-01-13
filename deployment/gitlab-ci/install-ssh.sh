#!/bin/bash
#
# Installs SSH key in docker container
#
# Usage:
#   chmod +x ./install-ssh.sh
#   ./install-ssh.sh <SSH_KEY> <SSH_USER> <SSH_HOST>
#

SSH_KEY=$1
SSH_USER=$2
SSH_HOST=$3

## Install ssh-agent if not already installed, it is required by Docker.
## (change apt-get to yum if you use an RPM-based image)
which ssh-agent || ( apt-get update -y && apt-get install openssh-client -y )
## Run ssh-agent (inside the build environment)
eval $(ssh-agent -s)
## Create the SSH directory and give it the right permissions
mkdir -p ~/.ssh
## Add the SSH key stored in SSH_PRIVATE_KEY variable to the agent store
## We're using tr to fix line endings which makes ed25519 keys work
## without extra base64 encoding.
## https://gitlab.com/gitlab-examples/ssh-private-key/issues/1#note_48526556
echo "${SSH_KEY}" | tr -d ' ' | base64 -d > ~/.ssh/${SSH_USER}
chmod 700 -R ~/.ssh
## Use ssh-keyscan to scan the keys of your private server. Replace gitlab.com
## with your own domain name. You can copy and repeat that command if you have
## more than one server to connect to.
ssh-keyscan ${SSH_HOST} >> ~/.ssh/known_hosts
