#!/bin/bash
for i in `find $1 -iname "*.png"`; do pngcrush -rem alla -reduce -brute $i "$i"2; mv "$i"2 $i; done;
