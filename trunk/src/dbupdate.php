<?php
/*
 * FreqStore - dbupdate.php
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
 

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

require("includes/config.php");
require("includes/functions.php");

$login = new login();
$sql = new sql();

// make sure user is logged in
if (!$login->checkLogin()) {
	header("Location: login.php");
	die();
}

// see if we should generate the state box
if (isset($_GET['generate']) && $_GET['generate'] == "50sdd") {
	if (!isset($_GET['selstate']) | $_GET['selstate'] == "") {
		$selected = "---";
	} else {
		$selected = strtoupper($_GET['selstate']);
	}
	
	$states = array('---','Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','NewHampshire','NewJersey','NewMexico','NewYork','NorthCarolina','NorthDakota','Ohio','Oklahoma','Oregon','Pennsylvania','RhodeIsland','SouthCarolina','SouthDakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','WestVirginia','Wisconsin','Wyoming');
	echo "<select width='10' id='state.0' name='states' onblur='update(this, \"state.0\")' onchange='update(this, \"state.0\")'>\n";
	for ($i = 0; $i < count($states); $i++) {
		echo "\t<option value='$states[$i]' ";
		if (strtoupper($states[$i]) == $selected) {
			echo "selected='selected'";
		}
		echo ">$states[$i]</option>\n";
	}
	echo "</select>";
	die();
} elseif (isset($_GET['generate']) && $_GET['generate'] == "sharebox") {
	if (!isset($_GET['share'])) {
		die("There was an error.");
	} else {
		$selected = $_GET['share'];
	}
	
	$values = array('0', '1', '2');
	$names  = array('Disabled', 'Private', 'Public');
	
	echo "<select id='share.0' name='share' onchange='update(this, \"share.0\", 1)'>\n";
	for ($i = 0; $i < count($names); $i++) {
		echo "\t<option value='$values[$i]' ";
		if ($names[$i] == $selected) {
			echo "selected='selected'";
		}
		echo ">$names[$i]</option>";
	}
	echo "</select>";
	die();
}

$userid = $_SESSION['userid'];
$dbid = $_GET['dbid'];

// get database listing from SQL
$row = $sql->query("SELECT * FROM frequencies WHERE tableid = '$dbid' AND userid = '$userid'");

if (!$row) {
	die("SQL Error1");
}

$freqs = unserialize($row['0']->frequency);
$alphatag = unserialize($row['0']->alphatag);
$description = unserialize($row['0']->description);
$location = unserialize($row['0']->location);
$numchans = $row['0']->numchans;

if (isset($_GET['fieldname'])) {
	$fieldname = explode(".", $_GET['fieldname']);
	$type = $fieldname['0'];
	$number = $fieldname['1'];
}

switch ($type) {
	case "f": // Update the frequency
		$freqs[$number] = $sql->sanitize($_GET['content']);
		$sqlfreqs = serialize($freqs);
		if (!$sql->insert("UPDATE frequencies SET frequency = '$sqlfreqs' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "a": // Update the alphatag
		$alphatag[$number] = $sql->sanitize($_GET['content']);
		$sqlalphatag = serialize($alphatag);
		if (!$sql->insert("UPDATE frequencies SET alphatag = '$sqlalphatag' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "d": // Update the description
		$description[$number] = $sql->sanitize($_GET['content']);
		$sqldesc = serialize($description);
		if (!$sql->insert("UPDATE frequencies SET description = '$sqldesc' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "name": // Update the table name
		$tablename = $sql->sanitize($_GET['content']);
		if (!$sql->insert("UPDATE frequencies SET tablename = '$tablename' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "city": // Update the city
		$location['0'] = $sql->sanitize($_GET['content']);
		$sqlloc = serialize($location);
		if (!$sql->insert("UPDATE frequencies SET location = '$sqlloc' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "state": // Update the state
		$location['1'] = $sql->sanitize($_GET['content']);
		$sqlloc = serialize($location);
		if (!$sql->insert("UPDATE frequencies SET location = '$sqlloc' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "county": // Update the county
		$location['2'] = $sql->sanitize($_GET['content']);
		$sqlloc = serialize($location);
		if (!$sql->insert("UPDATE frequencies SET location = '$sqlloc' WHERE tableid='$dbid' AND userid='$userid'")) {
			die("SQL Error");
		}
		break;
		
	case "share": // Update the share level
		$share = $sql->sanitize($_GET['content']);
		if (!$sql->insert("UPDATE frequencies SET public = '$share' WHERE tableid = '$dbid' AND userid = '$userid'")) {
			die("SQL Error2");
		}
		break;
	
	
}




?>














