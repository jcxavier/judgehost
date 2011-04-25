#!/bin/sh

TESTIN="$1"
PROGRAMOUT="$2"
SUBMISSION="$3"
OCIRUN="oci_run.php"
OCILIB="/home/domjudge-run/domjudge/judgehost/lib/judge/oci_lib.php"

USERNAME=`grep '^username.*' $TESTIN | awk '{ print $3 }'`
PASSWORD=`grep '^password.*' $TESTIN | awk '{ print $3 }'`
HOSTNAME=`grep '^hostname.*' $TESTIN | awk '{ print $3 }'`
PORT=`grep '^port.*' $TESTIN | awk '{ print $3 }'`
SID=`grep '^SID.*' $TESTIN | awk '{ print $3 }'`

SQL=`cat $SUBMISSION | awk '{ gsub("#testcase", ""); print $0 }'`

rm -f $OCIRUN

echo '<?php' >> $OCIRUN
echo 'require_once(\047'$OCILIB'\047);' >> $OCIRUN
echo '$sql = "'$SQL'";' >> $OCIRUN
echo '$conn = openConnection("'$USERNAME'", "'$PASSWORD'", "'$HOSTNAME'", "'$PORT'", "'$SID'");' >> $OCIRUN
echo '$stmt = execQuery($conn, $sql);' >> $OCIRUN
echo '$matrix = getRowMatrix($stmt);' >> $OCIRUN
echo 'closeConnection($conn);' >> $OCIRUN
echo 'var_export($matrix);' >> $OCIRUN
echo '?>' >> $OCIRUN

php $OCIRUN > $PROGRAMOUT

rm -f $OCIRUN

EXITCODE=$?

# EXITCODE = 1 indicates differences, others errors:
[ $EXITCODE -gt 1 ] && exit 1

exit 0
