#!/bin/bash

DB_LOADED=$(echo "select * from pages" | vendor/bin/typo3cms database:import)

if test -z "$DB_LOADED"
then
  echo 'loading db'
  ./vendor/bin/dep db:decompress local --options=dumpcode:dkfzStarterDb
  ./vendor/bin/dep db:import local --options=dumpcode:dkfzStarterDb
  ./vendor/bin/dep db:compress local --options=dumpcode:dkfzStarterDb
fi
