<?php
/**
 * Handle web submissions
 *
 * $Id: upload.php 3209 2010-06-12 00:13:43Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

/* for easy parsing of the status by the commandline websubmit client */
define('NONINTERACTIVE', isset($_REQUEST['noninteractive']));

require('init.php');
$title = 'Submit';

if ( ! ENABLE_WEBSUBMIT_SERVER ) {
	error("Websubmit disabled.");
}

if ( !isset($_POST['submit']) ) {
	if (NONINTERACTIVE) error("No 'submit' done.");
	header('Location: websubmit.php');
	return;
}
if ( is_null($cid) ) {
	require(LIBWWWDIR . '/header.php');
	echo "<p class=\"nodata\">No active contest</p>\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}
$now = now();
if ( difftime($cdata['starttime'], $now) > 0 ) {
	require(LIBWWWDIR . '/header.php');
	echo "<p class=\"nodata\">Contest has not yet started.</p>\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}


/** helper to output an error message. */
function err($string) {
	if (NONINTERACTIVE) error($string);

	echo '<div id="uploadstatus">';
	logmsg(LOG_WARNING, $string);
	echo '</div>';

	require(LIBWWWDIR . '/footer.php');
	exit;
}

ini_set("upload_max_filesize", SOURCESIZE * 1024);

require(LIBWWWDIR . '/header.php');

echo "<h2>Submit - upload status</h2>\n\n";

checkFileUpload($_FILES['code']['error']);

$filename = $_FILES['code']['name'];

/* Determine the problem */
$probid = @$_POST['probid'];

if ( empty($probid) ) {
	if ( strpos($filename, '.') === false ) {
		err('Unable to autodetect the problem from the uploaded filename');
	}
	$probid = strtolower(substr($filename, 0, strpos($filename, '.')));
}

$prob = $DB->q('MAYBETUPLE SELECT probid, name FROM problem
                WHERE allow_submit = 1 AND probid = %s AND cid = %i',
               $probid, $cid);

if ( ! isset($prob) ) err("Unable to find problem '$probid'");
$probid = $prob['probid'];

/* Determine the language */
$langext = @$_POST['langext'];

if ( empty($langext) ) {
	if ( strrpos($filename, '.') === false ) {
		err('Unable to autodetect the language from the uploaded filename');
	}
	$fileext = strtolower(substr($filename, strrpos($filename, '.')+1));

	$all_lang_exts = explode(" ", LANG_EXTS);

	foreach ($all_lang_exts as $langextlist) {
		$langexts = explode(",", $langextlist);

		// Skip first element: that's the language name
		for ($i = 1; $i < count($langexts); $i++) {
			if ( $langexts[$i]==$fileext ) $langext = $langexts[1];
		}
	}

	if ( empty($langext) ) err("Unable to find language for extension '$fileext'");
}

$lang = $DB->q('MAYBETUPLE SELECT langid, name, extension FROM language
                WHERE extension = %s AND allow_submit = 1', $langext);

if ( ! isset($lang) ) err("Unable to find language '$langext'");
$langext = $lang['extension'];

echo "<table>\n" .
	"<tr><td>Problem: </td><td><i>".htmlspecialchars($prob['name'])."</i></td></tr>\n" .
	"<tr><td>Language:</td><td><i>".htmlspecialchars($lang['name'])."</i></td></tr>\n" .
	"</table>\n";

submit_solution($login, $probid, $langext, $_FILES['code']['tmp_name']);

echo '<div id="uploadstatus">';
if (NONINTERACTIVE) echo '<!-- noninteractive-upload-successful -->';
echo "<p>Upload successful.</p>";
echo "</div>\n";

require(LIBWWWDIR . '/footer.php');
