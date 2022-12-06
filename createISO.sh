#!/bin/bash

# Build variables

LOGFILE=$ID.log

cp -r /usr/share/archiso/configs/releng/ $ID/archlive
echo "Copying files to $PWD/archlive" > $ID/$LOGFILE