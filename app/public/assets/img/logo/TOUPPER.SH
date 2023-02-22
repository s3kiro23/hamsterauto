#/bin/bash

for f in `ls ./`; do mv -- "$f" "$(tr [:lower:] [:upper:] <<< "$f")" ; done