#!/usr/bin/env bash

attributes=`grep filter=fat .gitattributes | awk '{print $1}'`
remote=`grep ^remote .gitfat | awk '{print $3}'`
user=`grep ^sshuser  .gitfat | awk '{print $3}'`

extensions=`echo $attributes | tr " " ":"`
#extensions=${extensions::-1}

echo $user $remote "$extensions"
