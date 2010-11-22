#!/bin/sh

USER=`defaults read co.tsun username`
PASS=`defaults read co.tsun password`
TEST="Testing\ New\ Quote"

curl -s -d "submit_quote=client" -d "quote=${TEST}" -d username=${USER} -d password=${PASS}  -H "X-Requested-With:XMLHttpRequest" http://tsun.co/quotes