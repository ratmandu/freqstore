<?php
/*
 * FreqStore - newdb.php
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
$page = new buildPage("New Database");
$login = new login();

if (!$login->checkLogin()) {
	header("Location: login.php");
	die();
}

if (isset($_POST['submit'])) {
	
	$tablename = $sql->sanitize($_POST['name']);
	$numchans = $sql->sanitize($_POST['numchans']);
	$city = $sql->sanitize($_POST['city']);
	$state = $sql->sanitize($_POST['state']);
	$county = $sql->sanitize($_POST['county']);
	$userid = $_SESSION['userid'];
	
	// check to make sure the tablename and number of channels was entered
	if (!($tablename) || !($numchans)) {
		header("Location: newdb.php?error=filled");
		die();
	}
	
	// generate dummy data for freqs, alphatags, and descriptions.
	$freqs = array();
	$alphatag = array();
	$description = array();
	
	for ($i = 0; $i < $numchans; $i++) {
		$freqs[$i] = "";
		$alphatag[$i] = "";
		$description[$i] = "";
	}
	
	$freqs = serialize($freqs);
	$alphatag = serialize($alphatag);
	$description = serialize($description);
	$location = array($city, $state, $county);
	$location = serialize($location);
	
	$result = $sql->insert("INSERT INTO frequencies(userid, tablename, location, numchans, frequency, alphatag, description) VALUES('$userid', '$tablename', '$location', '$numchans', '$freqs', '$alphatag', '$description')");
	
	if (!$result) {
		header("Location: newdb.php?error=sqlerr");
		die();
	} else {
		header("Location: userdb.php?dbid=$result");
	}
	
	
	
	
}

if (isset($_GET['error'])) {
	switch ($_GET['error']) {
		case "filled":
			$page->addError("Please make sure at least the table name and number of channels are filled in");
			break;
			
		case "sqlerr":
			$page->addError("There was an SQL Error. Please report this to an admin!");
			break;
	}
}

$pagecontent = <<<END1
<center>
	<form method="post" action="newdb.php">
		<table>
			<tr>
				<td>Table Name: </td>
				<td><input type="text" name="name" size="15"></td>
			</tr>
			<tr>
				<td>Number Of Channels: </td>
				<td><input type="text" name="numchans" size="15"></td>
			</tr>
			<tr>
				<td>City: </td>
				<td><input type="text" name="city" size="15"></td>
			</tr>
			<tr>
				<td>State: </td>
				<td>
END1;

$states = array('---','Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','NewHampshire','NewJersey','NewMexico','NewYork','NorthCarolina','NorthDakota','Ohio','Oklahoma','Oregon','Pennsylvania','RhodeIsland','SouthCarolina','SouthDakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','WestVirginia','Wisconsin','Wyoming');
$pagecontent .= "<select id='state' name='state'>\n";
for ($i = 0; $i < count($states); $i++) {
	$pagecontent .= "\t<option value='$states[$i]'>$states[$i]</option>\n";
}
$pagecontent .= "</select>";

$pagecontent .= <<<END2
				</td>
			</tr>
			<tr>
				<td>County: </td>
				<td><input type="text" name="county" size="15"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
	</form>
</center>
END2;


$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($pagecontent);
$page->addFooter();
$page->printPage();


?>