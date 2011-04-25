#!/bin/sh
# $Id: run_oracle 744 2011-04-26 00:18:13Z jcxavier $

# Run script for an Oracle DBMS to be called from 'testcase_run.sh'.
# See that script for more info.
#
# Usage: $0 <program> <testin> <output> <error> <exitfile>
#
# <program>   Executable of the program to be run.
# <testin>    File containing test-input.
# <output>    File where to write solution output.
# <error>     File where to write error messages.
# <exitfile>  File where to write solution exitcode.

PROGRAM="$1";   shift
TESTIN="$1";    shift
OUTPUT="$1";    shift
ERROR="$1";     shift
EXITFILE="$1";  shift

# The program output isn't calculated at this time. Copy it as its output.
cp $PROGRAM $OUTPUT
exitcode=$?

printf "$exitcode" >$EXITFILE

exit $exitcode
