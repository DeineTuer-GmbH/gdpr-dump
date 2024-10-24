#!/bin/bash

echo "Downloading AT address file ..."
wget -q https://files.openstreetdata.org/addresses/AT-houses.tsv.gz -O src/TsvProvider/AT-houses.tsv.gz
echo "Downloading CH address file ..."
wget -q https://files.openstreetdata.org/addresses/CH-houses.tsv.gz -O src/TsvProvider/CH-houses.tsv.gz
echo "Downloading DE address file ..."
wget -q https://files.openstreetdata.org/addresses/DE-houses.tsv.gz -O src/TsvProvider/DE-houses.tsv.gz

echo "Compiling PHAR ..."
make compile c="--locale=de_AT  --locale=de_CH --locale=de_DE"
echo "Done, Result can be found in build/dist"
