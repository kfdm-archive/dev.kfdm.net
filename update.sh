#!/bin/bash

#Change script to the location of this file
script_path=`dirname $0`
echo "---Moving to ($script_path)---"
cd $script_path

#Update From Git
echo "---Update Master---"
git pull --rebase origin master

echo "---Update submodules---"
git submodule update
