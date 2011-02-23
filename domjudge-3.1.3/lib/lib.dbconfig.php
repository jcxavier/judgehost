<?php
/**
 * Functions for handling database stored configuration.
 *
 * $Id: lib.dbconfig.php 3209 2010-06-12 00:13:43Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

function dbconfig_get($name, $default, $cacheok = true)
{
	global $LIBDBCONFIG;

        if ( (!isset($LIBDBCONFIG)) || (!$cacheok) ) {
		dbconfig_init();
	}

	if ( isset($LIBDBCONFIG[$name]) ) return $LIBDBCONFIG[$name];

	return $default;
}

function dbconfig_init()
{
	global $LIBDBCONFIG, $DB;
	$LIBDBCONFIG = $DB->q('KEYVALUETABLE SELECT name,value FROM configuration');
}

function dbconfig_set($name, $value)
{
	global $LIBDBCONFIG, $DB;
	$DB->q('REPLACE INTO configuration (name,value) VALUES (%s,%s)', $name, $value);
	$LIBDBCONFIG[$name] = $value;
}
