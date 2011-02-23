<?php
/**
 * Document relations between DOMjudge tables for various use.
 *
 * $Id: relations.php 3404 2010-10-27 19:56:39Z kink $
 */

/** For each table specify the set of attributes that together
 *  are considered the primary key / superkey. */
$KEYS = array();
$KEYS['clarification'] = array('clarid');
$KEYS['contest'] = array('cid');
$KEYS['event'] = array('eventid');
$KEYS['judgehost'] = array('hostname');
$KEYS['judging'] = array('judgingid');
$KEYS['judging_run'] = array('runid');
$KEYS['language'] = array('langid');
$KEYS['problem'] = array('probid');
$KEYS['scoreboard_jury'] = array('cid','teamid','probid');
$KEYS['scoreboard_public'] = array('cid','teamid','probid');
$KEYS['submission'] = array('submitid');
$KEYS['team'] = array('login');
$KEYS['team_affiliation'] = array('affilid');
$KEYS['team_category'] = array('categoryid');
$KEYS['team_unread'] = array('teamid','mesgid','type');
$KEYS['testcase'] = array('testcaseid');


/** For each table, list all attributes that reference foreign keys
 *  and specify the source of that key. */
$RELATIONS = array();

$RELATIONS['clarification'] = array (
	'cid' => 'contest.cid',
	'respid' => 'clarification.clarid',
	'sender' => 'team.login',
	'recipient' => 'team.login'
);

$RELATIONS['contest'] = array();

$RELATIONS['event'] = array (
	'cid' => 'contest.cid',
	'clarid' => 'clarification.clarid',
	'langid' => 'language.langid',
	'probid' => 'problem.probid',
	'submitid' => 'submission.submitid',
	'teamid' => 'team.login'
);

$RELATIONS['judgehost'] = array();

$RELATIONS['judging'] = array (
	'cid' => 'contest.cid',
	'submitid' => 'submission.submitid',
	'judgehost' => 'judgehost.hostname'
);

$RELATIONS['judging_run'] = array (
	'judgingid' => 'judging.judgingid',
	'testcaseid' => 'testcase.testcaseid'
);

$RELATIONS['language'] = array();

$RELATIONS['problem'] = array (
	'cid' => 'contest.cid'
);

$RELATIONS['submission'] = array (
	'cid' => 'contest.cid',
	'teamid' => 'team.login',
	'probid' => 'problem.probid',
	'langid' => 'language.langid',
	'judgehost' => 'judgehost.hostname'
);

$RELATIONS['team'] = array (
	'categoryid' => 'team_category.categoryid',
	'affilid' => 'team_affiliation.affilid'
);

$RELATIONS['team_affiliation'] = array();

$RELATIONS['team_category'] = array();

$RELATIONS['team_unread'] = array(
	'teamid' => 'team.login'
	// can't check mesgid
);

$RELATIONS['testcase'] = array(
	'probid' => 'problem.probid'
);

/**
 * Check whether some primary key is referenced in any
 * table as a foreign key.
 *
 * Returns null or the table name if a match is found.
 */
function fk_check ($keyfield, $value) {
	global $RELATIONS, $DB;

	foreach ( $RELATIONS as $table => $keys ) {
		foreach ( $keys as $key => $foreign ) {
			if ( $foreign == $keyfield ) {
				$c = $DB->q("VALUE SELECT count(*) FROM $table WHERE $key = %s",
					$value);
				if ( $c > 0 ) return $table;
			}
		}
	}

	return null;
}
