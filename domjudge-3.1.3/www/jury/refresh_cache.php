<?php

/**
 * Recalculate all cached data in DOMjudge:
 * - The scoreboard.
 * Use this sparingly since it requires
 * (3 x #teams x #problems) queries.
 *
 * $Id: refresh_cache.php 3170 2010-05-19 18:18:00Z kink $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
$title = 'Refresh Cache';
require(LIBWWWDIR . '/header.php');
require(LIBWWWDIR . '/scoreboard.php');

requireAdmin();

// no output buffering... we want to see what's going on real-time
ob_implicit_flush();

echo "<h1>Refresh Cache</h1>\n\n";

// get the contest, teams and problems
$teams = $DB->q('TABLE SELECT login FROM team ORDER BY login');
$probs = $DB->q('COLUMN SELECT probid FROM problem
                 WHERE cid = %i ORDER BY probid', $cid);

echo "<p>Recalculating all values for the scoreboard cache (" .
	count($teams) . " teams, " . count($probs) ." problems, contest c" .
	htmlspecialchars($cid) . ")...</p>\n\n<pre>\n";

if ( count($teams) == 0 ) {
	echo "No teams defined, doing nothing.</pre>\n\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}
if ( count($probs) == 0 ) {
	echo "No problems defined, doing nothing.</pre>\n\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}

$teamlist = array();

// for each team, fetch the status of each problem
foreach( $teams as $team ) {

	$teamlist[] = $team['login'];

	echo "Team " . htmlspecialchars($team['login']) . ":";

	// for each problem fetch the result
	foreach( $probs as $pr ) {
		echo " " .htmlspecialchars($pr);
		calcScoreRow($cid, $team['login'], $pr);
	}

	echo "\n";
}

echo "</pre>\n\n<p>Deleting irrelevant data...</p>\n\n";

// drop all contests that are not current, teams and problems that do not exist
$DB->q('DELETE FROM scoreboard_jury
        WHERE cid != %i OR teamid NOT IN (%As) OR probid NOT IN (%As)',
       $cid, $teamlist, $probs);
$DB->q('DELETE FROM scoreboard_public
        WHERE cid != %i OR teamid NOT IN (%As) OR probid NOT IN (%As)',
       $cid, $teamlist, $probs);

echo "<p>Finished.</p>\n\n";

require(LIBWWWDIR . '/footer.php');
