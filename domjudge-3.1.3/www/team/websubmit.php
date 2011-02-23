<?php
/**
 * Web submissions form
 *
 * $Id: websubmit.php 3490 2010-12-05 16:06:01Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');

if ( ! ENABLE_WEBSUBMIT_SERVER ) {
	error("Websubmit disabled.");
}

$title = 'Submit';
require(LIBWWWDIR . '/header.php');
require(LIBWWWDIR . '/forms.php');

if ( is_null($cid) ) {
	echo "<p class=\"nodata\">No active contest</p>\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}
if ( difftime($cdata['starttime'], now()) > 0 ) {
	echo "<p class=\"nodata\">Contest has not yet started.</p>\n";
	require(LIBWWWDIR . '/footer.php');
	exit;
}

echo "<script type=\"text/javascript\">\n<!--\n";
echo "function getLangNameFromExtension(ext)\n{\n";
echo "\tswitch(ext) {\n";
$exts = explode(" ", LANG_EXTS);
foreach($exts as $ext) {
	$langexts = explode(',', $ext);
	for ($i = 1; $i < count($langexts); $i++) {
		echo "\t\tcase '" . $langexts[$i]. "': return '" .$langexts[0] . "';\n";
	}
}
echo "\t\tdefault: return '';\n\t}\n}\n";
echo "// -->\n</script>\n";

// Put overview of team submissions (like scoreboard)
echo "<div id=\"teamscoresummary\">\n";
putTeamRow($cdata, $login);
echo "</div>\n";

echo "<h1>New Submission</h1>\n\n";

echo addForm('upload.php','post',null,'multipart/form-data');

?>

<table>
<tr><td><label for="code">File</label>:</td>
<td><input type="file" name="code" id="code" size="40" onChange='detectProblemLanguage(document.getElementById("code").value);' /></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td><label for="probid">Problem</label>:</td>
    <td><?php

$probs = $DB->q('KEYVALUETABLE SELECT probid, CONCAT(probid,": ",name) as name FROM problem
                 WHERE cid = %i AND allow_submit = 1
                 ORDER BY probid', $cid);

if( count($probs) == 0 ) {
	error('No problems defined for this contest');
}

$probs[''] = 'by filename';
echo addSelect('probid', $probs, '', true);

?></td>
</tr>
<tr><td><label for="langext">Language</label>:</td>
    <td><?php

$langs = $DB->q('KEYVALUETABLE SELECT extension, name FROM language
                 WHERE allow_submit = 1 ORDER BY name');

if( count($langs) == 0 ) {
	error('No languages defined');
}

$langs[''] = 'by extension';
echo addSelect('langext', $langs, '', true);

?></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td></td>
    <td><?php echo addSubmit('Submit solution', 'submit',
               "return confirm(getUploadConfirmString());"); ?></td>

</tr>
</table>

<?php

echo addEndForm();

require(LIBWWWDIR . '/footer.php');
