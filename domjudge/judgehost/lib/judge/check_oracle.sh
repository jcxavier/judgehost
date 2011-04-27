#!/bin/sh
# $Id: check_oracle.sh 1254 2011-04-26 00:18:55Z jcxavier $

# Runs a comparison between two queries in an Oracle DBMS system.

TESTIN="$1"
PROGRAM="$2"
TESTOUT="$3"

DJ_LIBDIR="/home/domjudge-run/domjudge/judgehost/lib"

OCICOMPARE="oci_compare.php"
OCILIB="$DJ_LIBDIR/judge/oci_lib.php"

SOLUTIONOUT="__solution_out"
SUBMISSIONOUT="__submission_out"

rm -f $SOLUTIONOUT $SUBMISSIONOUT

# Generate the submission query and the solution query:
$DJ_LIBDIR/judge/runjury_oracle.sh $TESTIN $SUBMISSIONOUT $PROGRAM
$DJ_LIBDIR/judge/runjury_oracle.sh $TESTIN $SOLUTIONOUT $TESTOUT

SOLUTIONARRAY=`cat $SOLUTIONOUT`
SUBMISSIONARRAY=`cat $SUBMISSIONOUT`

rm -f $SOLUTIONOUT $SUBMISSIONOUT $OCICOMPARE

# Compile a PHP script to compare both queries:
echo '<?php' >> $OCICOMPARE
echo 'require_once(\047'$OCILIB'\047);' >> $OCICOMPARE
echo '$matrixSol = '$SOLUTIONARRAY';' >> $OCICOMPARE
echo '$matrixSub = '$SUBMISSIONARRAY';' >> $OCICOMPARE
echo 'if (isset($matrixSub[\047error\047])) {' >> $OCICOMPARE
echo 'echo $matrixSub[\047error\047]; exit(2); }' >> $OCICOMPARE
echo '$errors = compareMatrixes($matrixSol, $matrixSub);' >> $OCICOMPARE
echo 'printPretty($errors);' >> $OCICOMPARE
echo '?>' >> $OCICOMPARE

# Exitcode will be different than 0 if there were errors compiling either query.
php $OCICOMPARE
EXITCODE=$?

rm -f $OCICOMPARE


[ $EXITCODE -gt 1 ] && exit 1

exit 0
