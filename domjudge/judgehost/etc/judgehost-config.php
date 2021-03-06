<?php

require_once("common-config.php");

// Default config

/** Restrictions during testing */

// Run solutions in a chroot environment? (gives better security)
define('USE_CHROOT', false);

// Optional script to run for creating/destroying chroot environment,
// leave empty to disable. This example script can be used to support
// Sun Java with a chroot (edit the script first!).
// define('CHROOT_SCRIPT', 'chroot-startstop.sh');
define('CHROOT_SCRIPT', '');

// Maximum seconds available for compiling
define('COMPILETIME', 30);

// Maximum memory usage by RUNUSER in kB
// This includes the shell which starts the compiled solution and
// also any interpreter like Sun 'java', which takes 200MB away!
define('MEMLIMIT', 524288);

// Maximum filesize RUNUSER may write in kB
// This should be greater than the maximum testdata output, otherwise
// solutions will abort before writing the complete correct output!!
define('FILELIMIT', 4096);

// Maximum no. processes running as RUNUSER (including shell and
// possibly interpreters)
define('PROCLIMIT', 15);

/** Possible results and their priorities */

// Priority of results for determining final result with multiple
// testcases. Higher priority is used first as final result. With
// equal priority, the first occurring result determines the final
// result. The default below tries to simulate single testcase
// behaviour.
$RESULTS_PRIO = array(
	'memory-limit'       => 99,
	'output-limit'       => 99,
	'run-error'          => 99,
	'timelimit'          => 99,
	'wrong-answer'       => 30,
	'presentation-error' => 20,
	'no-output'          => 10,
	'correct'            =>  1,
	);

// Lazy evaluation of results? If enabled, returns final result as
// soon as a highest priority result is found, otherwise only return
// final result when all testcases are judged.
// Note that may especially speed up judging when timelimit has
// highest priority and a solution would otherwise timeout on a lot of
// testcases.
define('LAZY_EVAL_RESULTS', true);

// Remap results, e.g. to disable a specific result. Some possible
// options are shown commented below.
$RESULTS_REMAP = array(
//	'presentation-error' => 'wrong-answer',
//	'presentation-error' => 'correct',
//	'no-output'          => 'wrong-answer',
	);
