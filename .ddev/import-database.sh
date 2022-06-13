#!/bin/bash

DB_LOADED=$(echo "select * from pages" | vendor/bin/typo3cms database:import)

if test -z "$DB_LOADED"
then
  echo 'loading db'
  ./vendor/bin/dep db:decompress --options=dumpcode:dkfzStarterDb
  ./vendor/bin/dep db:import --options=dumpcode:dkfzStarterDb
fi
