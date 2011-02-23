<?php
/**
 * View team details
 *
 * $Id: team.php 2539 2008-12-01 19:46:36Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

$pagename = basename($_SERVER['PHP_SELF']);

$id = $_REQUEST['id'];

require('init.php');

if ( ! $id || preg_match('/\W/', $id) ) error("Missing or invalid team id");

$title = 'Team '.htmlspecialchars(@$id);
require(LIBWWWDIR . '/header.php');

putTeam($id);

require(LIBWWWDIR . '/footer.php');
