#!/bin/sh
# $Id: check_oracle.sh 665 2011-04-24 19:44:52Z jcxavier $

# Simple program for compare_program.sh script: this just runs a diff
# comparison between the program and expected output. Should give same
# results as the default compare.sh script.
#
# Additional options to diff can be passed here, like:
#  -i    Ignore case differences.
#  -b    Ignore changes in the amount of white space.
#  -B    Ignore changes whose lines are all blank.
# and see 'man diff' for more options.

TESTIN="$1"
PROGRAM="$2"
TESTOUT="$3"

DJ_LIBDIR="/home/domjudge-run/domjudge/judgehost/lib"

OCICOMPARE="oci_compare.php"
OCILIB="$DJ_LIBDIR/judge/oci_lib.php"

SOLUTIONOUT="__solution_out"
SUBMISSIONOUT="__submission_out"

rm -f $SOLUTIONOUT $SUBMISSIONOUT

$DJ_LIBDIR/judge/runjury_oracle.sh $TESTIN $SUBMISSIONOUT $PROGRAM
$DJ_LIBDIR/judge/runjury_oracle.sh $TESTIN $SOLUTIONOUT $TESTOUT

SOLUTIONARRAY=`cat $SOLUTIONOUT`
SUBMISSIONARRAY=`cat $SUBMISSIONOUT`

rm -f $SOLUTIONOUT $SUBMISSIONOUT $OCICOMPARE


echo '<?php' >> $OCICOMPARE
echo 'require_once(\047'$OCILIB'\047);' >> $OCICOMPARE
echo '$matrixSol = '$SOLUTIONARRAY';' >> $OCICOMPARE
echo '$matrixSub = '$SUBMISSIONARRAY';' >> $OCICOMPARE
echo '$errors = compareMatrixes($matrixSol, $matrixSub);' >> $OCICOMPARE
echo 'printPretty($errors);' >> $OCICOMPARE
echo '?>' >> $OCICOMPARE

php $OCICOMPARE

rm -f $OCICOMPARE

EXITCODE=$?

# EXITCODE = 1 indicates differences, others errors:
[ $EXITCODE -gt 1 ] && exit 1

exit 0
