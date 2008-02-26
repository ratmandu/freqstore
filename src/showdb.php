<?php
/*
 * FreqStore - showdb.php
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
$page = new buildPage("Show Database");
$login = new login();

if (!isset($_GET['dbid']) | $_GET['dbid'] == "") {
	$page->addError("There was an error retrieving the database.<br> Please check your link and try again.", 1);
}

$dbid = $_GET['dbid'];

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
$urlcode = $_GET['unique'];

// make sure that the database is shared, and that the code provided matches the one in the database
if (($urlcode != $sharecode) | ($sharestatus == "0")) {
	$page->addError("The database you attempted to view is either private, or you followed a bad link.<br>Please check the link and try again", true);
}

// get owners username
$row2 = $sql->query("SELECT * FROM users WHERE userid = '$tableuser'");
$username = $row2['0']->username;

// print results in nice table
$infobox .= <<<HERE1
<center>
	<table width="100%">
		<tr>
			<td align="center">Table Name: <b>$tablename</b><br>Owner: <b>$username</b></td>
			<td align="center">City: <b>$city</b><br>
								State: <b>$state</b><br>
								County: <b>$county</b>
			</td>
		</tr>
	</table>
</center>
HERE1;

$freqtable = <<<HERE2
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
		$freqtable .= "<td>---</td>\n";
	} else {
		$freqtable .= "<td>".$freqs[$i]."</td>\n";
	}
	
	if ($alphatag[$i] == "") {
		$freqtable .= "<td>---</td>\n";
	} else {
		$freqtable .= "<td>".$alphatag[$i]."</td>\n";
	}
	
	if ($description[$i] == "") {
		$freqtable .= "<td>---</td>\n";
	} else {
		$freqtable .= "<td>".$description[$i]."</td>\n";
	}
	
	$freqtable .= "</tr>\n";
	
	
	
}

$freqtable .= "</table>\n\t</center>";

$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($infobox);
$page->addContent($freqtable);
$page->addFooter();
$page->printPage();




?>