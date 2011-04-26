#!/bin/sh
# $Id: compare_oracle.sh 2242 2011-04-26 00.19.43Z jcxavier $

# Compare script for an Oracle DBMS to be called from 'testcase_run.sh'.
#
# This script is written to comply with the ICPC Validator Interface Standard
# as described in http://www.ecs.csus.edu/pc2/doc/valistandard.html.
#
# Usage: $0 <testdata.in> <program.out> <testdata.out> <result.xml> <diff.out>
#
# <testdata.in>   File containing testdata input.
# <program.out>   File containing the program output.
# <testdata.out>  File containing the correct output.
# <result.xml>    File containing an XML document describing the result.
# <diff.out>      File to write program/correct output differences to (optional).
#
#
# Exits successfully except when an internal error occurs. Submitted
# program output is considered correct when diff.out is empty (and
# specified).
#
# This script calls another (configurable) program to check the
# results. Calling syntax:
#    $CHECK_PROGRAM <testdata.in> <program.out> <testdata.out>
#
# The $CHECK_PROGRAM should return the contents of <diff.out> to
# standard output. It must exit with exitcode zero to indicate
# successful checking.

# Check program, specify with absolute path or use `dirname $0`:
CHECK_PROGRAM="`dirname $0`/check_oracle.sh"

# Options to pass to check program:
CHECK_OPTIONS=""

TESTIN="$1"
PROGRAM="$2"
TESTOUT="$3"
RESULT="$4"
DIFFOUT="$5"

writeresult()
{
    ( cat <<EOF
<?xml version="1.0"?>
<!DOCTYPE result [
  <!ELEMENT result (#PCDATA)>
  <!ATTLIST result outcome CDATA #REQUIRED>
]>
<result outcome="$1">"$2"</result>
EOF
    ) > "$RESULT"
}

if [ ! -x "$CHECK_PROGRAM" ]; then
	echo "Error: '$CHECK_PROGRAM' not found or executable." >&2
	writeresult "Internal error" "Internal error"
	exit 1
fi

# Run the program:
"$CHECK_PROGRAM" $CHECK_OPTIONS "$TESTIN" "$PROGRAM" "$TESTOUT" > "$DIFFOUT"
EXITCODE=$?

# Compile error, when non-zero exitcode found:
if [ $EXITCODE -ne 0 ]; then
	echo "Compiler error: '$CHECK_PROGRAM' exited with exitcode $EXITCODE." >&2
	writeresult "Compiler error" "Error compiling SQL query."
	exit 0
fi

# Check result and write result file:
if [ -s "$DIFFOUT" ]; then
    WHY=`cat $DIFFOUT`
	writeresult "Wrong answer" "$WHY"
else
	writeresult "Accepted" "Accepted"
fi

exit 0
