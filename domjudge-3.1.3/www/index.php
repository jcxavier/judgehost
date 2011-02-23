<?php

/**
 * Switch a user to the right site based on whether they can be
 * autheticated as team, jury, or nothing (public).
 *
 * $Id: index.php 3183 2010-05-30 07:13:41Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require_once('configure.php');

require_once(LIBDIR . '/lib.error.php');
require_once(LIBDIR . '/lib.misc.php');
require_once(LIBDIR . '/use_db.php');
// Team login necessary for checking login credentials:
setup_database_connection('team');

require_once(LIBWWWDIR . '/common.php');
require_once(LIBWWWDIR . '/auth.team.php');

if ( logged_in() ) {
	$target = 'team/';
} else if ( false ) { /* FIXME: test jury login? */
	$target = 'jury/';
} else {
	$target = 'public/';
}

header('HTTP/1.1 302 Please see this page');
header('Location: ' . $target);
