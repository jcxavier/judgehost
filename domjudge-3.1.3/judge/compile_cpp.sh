#!/bin/sh

# C++ compile wrapper-script for 'compile.sh'.
# See that script for syntax and more info.

SOURCE="$1"
DEST="$2"

# -Wall:	Report all warnings
# -O2:		Level 2 optimizations (default for speed)
# -static:	Static link with all libraries
# -pipe:	Use pipes for communication between stages of compilation
g++ -Wall -O2 -static -pipe -o $DEST $SOURCE
exit $?
