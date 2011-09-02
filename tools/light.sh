#!/bin/bash
# @see http://webmoney.ru/rus/developers/interfaces/xml/xml_php/index.shtml

key=""
cer=""

if [ ${1: -4} == ".pfx" ]; then
    key=${1%".pfx"}.key
    cer=${1%".pfx"}.cer
elif [ ${1: -4} == ".p12" ]; then
    key=${1%".p12"}.key
    cer=${1%".p12"}.cer
else
    echo "Error: file should be .pfx or .p12"
    exit 1
fi

openssl pkcs12 -in $1 -out $key -nocerts
openssl pkcs12 -in $1 -out $cer -clcerts -nokeys
