#!/bin/sh
# $Id: runjury_oracle 1417 2011-04-26 00:18:31Z jcxavier $

# Runs a query in an Oracle DBMS system.

TESTIN="$1"
PROGRAMOUT="$2"
SUBMISSION="$3"
OCIRUN="oci_run.php"
OCILIB="/home/domjudge-run/domjudge/judgehost/lib/judge/oci_lib.php"

# Get the connection information from the input test data:
USERNAME=`grep '^username.*' $TESTIN | awk '{ print $3 }'`
PASSWORD=`grep '^password.*' $TESTIN | awk '{ print $3 }'`
HOSTNAME=`grep '^hostname.*' $TESTIN | awk '{ print $3 }'`
PORT=`grep '^port.*' $TESTIN | awk '{ print $3 }'`
SID=`grep '^SID.*' $TESTIN | awk '{ print $3 }'`

# Remove all "#testcase" and ";" from the query:
SQL=`cat $SUBMISSION | awk '{
    gsub("#testcase", "");
    gsub("\073", "");
    print $0 }'`

rm -f $OCIRUN

# Compile a PHP script to retrieve the query result:
echo '<?php' >> $OCIRUN
echo 'require_once(\047'$OCILIB'\047);' >> $OCIRUN
echo '$sql = "'"$SQL"'";' >> $OCIRUN
echo '$conn = openConnection("'$USERNAME'", "'$PASSWORD'", "'$HOSTNAME'", "'$PORT'", "'$SID'");' >> $OCIRUN
echo '$stmt = execQuery($conn, $sql);' >> $OCIRUN
echo '$matrix = getRowMatrix($stmt);' >> $OCIRUN
echo 'closeConnection($conn);' >> $OCIRUN
echo 'var_export($matrix);' >> $OCIRUN
echo '?>' >> $OCIRUN

# Output the matrix to a file to be compared later:
php $OCIRUN > $PROGRAMOUT
EXITCODE=$?

rm -f $OCIRUN

# EXITCODE = 1 indicates differences, others errors:
[ $EXITCODE -gt 1 ] && exit 1

exit 0
