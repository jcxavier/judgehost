<?php
/**
 * Include required files.
 *
 * $Id: init.php 3146 2010-02-27 10:55:28Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require_once('../configure.php');

/* For plugins to have jury access rights to the DB, they should
 * successfully authenticate as user 'jury'.
 */
define('IS_JURY', (@$_SERVER['REMOTE_USER'] == "jury"));

if( DEBUG & DEBUG_TIMINGS ) {
	require_once(LIBDIR . '/lib.timer.php');
}

require_once(LIBDIR . '/lib.error.php');
require_once(LIBDIR . '/lib.misc.php');
require_once(LIBDIR . '/lib.dbconfig.php');
require_once(LIBDIR . '/use_db.php');

set_exception_handler('exception_handler');
setup_database_connection('plugin');

require_once(LIBWWWDIR . '/common.php');
require_once(LIBWWWDIR . '/print.php');

$cdata = getCurContest(TRUE);
$cid = (int)$cdata['cid'];
