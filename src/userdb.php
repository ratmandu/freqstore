<?php
/*
 * FreqStore - userdb.php
 * Copyright (C) 2008 Justin Richards
 * Released under the GNU General Public License v3
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */
 
require("includes/config.php");
require("includes/functions.php");

$sql = new sql();
if (!isset($_GET['notemplate'])) {
	$page = new buildPage("Show Database");
}
$login = new login();

if (!$login->checkLogin()) {
	header("Location: login.php");
	die();
}

if (!isset($_GET['dbid']) | $_GET['dbid'] == "") {
	$page->addError("There was an error retrieving the database.<br> Please check your link and try again.", 1);
}

$dbid = $_GET['dbid'];
$userid = $_SESSION['userid'];

// get info from database
$row = $sql->query("SELECT * FROM frequencies WHERE tableid = '$dbid'");

$tableuser = $row['0']->userid;
$tablename = $row['0']->tablename;
$location = unserialize($row['0']->location);
$city = $location['0'];
$state = $location['1'];
$county = $location['2'];
$lastedit = $row['0']->lastedit;
$numchans = $row['0']->numchans;
$freqs = unserialize($row['0']->frequency);
$alphatag = unserialize($row['0']->alphatag);
$description = unserialize($row['0']->description);
$sharestatus = $row['0']->public;
$sharecode = $row['0']->sharecode;

if ($city == "") {
	$city = "---";
}

if ($state == "") {
	$state = "---";
}

if ($county == "") {
	$county = "---";
}

// Make sure the database belongs to the user
if ($tableuser != $userid) {
	$page->addError("There was an error displaying the table to you.<br>\nPlease make sure you followed the correct link.", 1);
}

// print results in nice table
$infobox .= <<<HERE1
<script type='text/javascript'>
setVarsForm('dbid=$dbid');
</script> 
<center>
	<table width="100%">
		<tr>
			<td align="center">Table Name: <b><span id='name.0' onclick='textBoxIt("name.0");'>$tablename</span></b><br>Number Of Chans: <b>$numchans</b></td>
			<td align="center">City: <b><span id='city.0' onclick='textBoxIt("city.0");'>$city</span></b><br>
								State: <b><span id='state.0' onclick='stateSelect("state.0");'>$state</span></b><br>
								County: <b><span id='county.0' onclick='textBoxIt("county.0");'>$county</span></b>
			</td>
		</tr>
	</table>
</center>
HERE1;

if ($sharestatus == "0") {
	$sharestat = "<span class='shareoff'>Disabled</span>";
	$sharelink = "";
} elseif ($sharestatus == "1") {
	$sharestat = "<span class='sharepriv'>Private</span>";
	$sharelink = "<br />\n<input type='text' size='55' value='".SITE_URL."showdb.php?dbid=$dbid&unique=$sharecode'></input>";
} elseif ($sharestatus == "2") {
	$sharestat = "<span class='shareon'>Public</span>";
	$sharelink = "<br />\n<input type='text' size='55' value='".SITE_URL."showdb.php?dbid=$dbid&unique=$sharecode'></input>";
}

if (!isset($_GET['nosharetemp'])) {
	$sharebox .= "<div id='share'>\n";
}

$sharebox .= <<<HERE3
<center>
	Sharing: <b><span id='share.0' onclick='shareBox("share.0");'>$sharestat</span></b>
	$sharelink
</center>
HERE3;

if (isset($_GET['nosharetemp'])) {
	echo $sharebox;
	die();
} else {
	$sharebox .= "</div>\n";
}
								
if (!isset($_GET['notemplate'])) {
	$freqtable .= "<div id='table'>\n";
}
								
$freqtable .= <<<HERE2
<center>
	<table width="100%" cellpadding="2" cellspacing="0" class="solidborder">
		<tr class="striped">
			<td width="20"><b>#</b></td>
			<td width="120"><b>Frequency</b></td>
			<td width="200"><b>Alpha Tag</b></td>
			<td width="200"><b>Description</b></td>
		</tr>\n
HERE2;
	
$stripe = 0;

for ($i = 0; $i < $numchans; $i++) {
	if ($stripe) {
		$freqtable .= "<tr class=\"striped\">\n";
		$stripe = 0;
	} else {
		$freqtable .= "<tr>\n";
		$stripe = 1;
	}
	
	$freqtable .= "<td>".($i+1)."</td>\n";
	
	if ($freqs[$i] == "") {
		$freqtable .= "<td><span id='f.$i' onclick='textBoxIt(\"f.$i\");'>---</span></td>\n";
	} else {
		$freqtable .= "<td><span id='f.$i' onclick='textBoxIt(\"f.$i\");'>".$freqs[$i]."</span></td>\n";
	}
	
	if ($alphatag[$i] == "") {
		$freqtable .= "<td><span id='a.$i' onclick='textBoxIt(\"a.$i\");'>---</span></td>\n";
	} else {
		$freqtable .= "<td><span id='a.$i' onclick='textBoxIt(\"a.$i\");'>".$alphatag[$i]."</span></td>\n";
	}
	
	if ($description[$i] == "") {
		$freqtable .= "<td><span id='d.$i' onclick='textBoxIt(\"d.$i\");'>---</span></td>\n";
	} else {
		$freqtable .= "<td><span id='d.$i' onclick='textBoxIt(\"d.$i\");'>".$description[$i]."</span></td>\n";
	}
	
	$freqtable .= "</tr>\n";
}

$freqtable .= "</table>\n\t</center>";

if (isset($_GET['notemplate'])) {
	echo $freqtable;
	die();
}

if (!isset($_GET['notemplate'])) {
	$freqtable .= "</div>\n";
}

$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($infobox);
$page->addContent($sharebox);
$page->addContent($freqtable);
$page->addFooter();
$page->printPage();

?>