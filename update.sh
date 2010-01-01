#!/bin/bash

#Change script to the location of this file
script_path=`dirname $0`
echo "---Moving to ($script_path)---"
cd $script_path

#Update From Git
echo "---Update Master---"
git stash
git pull --rebase origin master
git stash apply
