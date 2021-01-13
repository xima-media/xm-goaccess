#!/bin/bash

#
# Test of project initialization
#
# Usage:
#   Run ./init/test.sh from project root directory
#

# Configuration
BUILD_DIR='./.Build'

# Test
echo -e "\033[92m **** Test Project Initialization ****"

echo -e "\033[94m[INFO]\033[m Create test environment in $BUILD_DIR"
rm -rf $BUILD_DIR
mkdir $BUILD_DIR
cd $BUILD_DIR
cp -R ../.ddev .ddev
cp -R ../deployment deployment
cp -R ../htdocs htdocs
cp -R ../init init
cp ../.gitlab-ci.yml.dist .gitlab-ci.yml.dist
cp ../README.md README.md
cp ../sonar-project.properties sonar-project.properties

echo -e "\033[94m[INFO]\033[m Create Python venv"
python3 -m venv ./venv
#
echo -e "\033[94m[INFO]\033[m Install Python requirements"
venv/bin/pip3 install -r ./init/requirements.txt

echo -e "\033[94m[INFO]\033[m Start initialization script"
./init/init.py

echo
echo -e '\033[92m \xE2\x9C\x94 Test ends without errors.\033[m'
echo

