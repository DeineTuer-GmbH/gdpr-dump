This folder is custom created by DeineTür GmbH (THille)

The [countrycode]-addresses.tsv.gz files are downloaded from https://openstreetdata.org.
To update these files you can visit the website and download the "Houses"-File and replace the existing files in this folder.

The Houses file is used because it contains all existing street numbers whereas the "Address" file only contains a from and a to.
This leaves out e.g. house numbers like "5a".

Beware: due to the size of the files the usage of this feature takes a ton of ram when anonymizing.
While debugging I observed a usage of 13,4GB (`memory_get_peak_usage(true)`).
