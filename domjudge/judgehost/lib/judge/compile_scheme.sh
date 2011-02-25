#!/bin/sh

# Scheme compile wrapper-script for 'compile.sh'.
# See that script for syntax and more info.

SOURCE="$1"
DEST="$2"

mzc --exe $DEST $SOURCE
exit $?
