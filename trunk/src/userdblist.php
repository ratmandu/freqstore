<?php
/*
 * FreqStore - userdblist.php
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
$page = new buildPage("Database List");
$login = new login();

if (!$login->checkLogin()) {
	header("Location: login.php");
	die();
}

$userid = $_SESSION['userid'];

$row = $sql->query("SELECT * FROM frequencies WHERE userid = '$userid'");

if (!$row) {
	$page->addMessage("You have no frequency databases yet.<br>\nPlease <a href='newdb.php'>Create One</a>", 1);
}

$pagecontent .= <<<HERE1
<center>
	<table width="100%" cellpadding="2" cellspacing="0" class="solidborder">
		<tr>
			<td><b>Database Name</b></td>
			<td><b>Number Of Channels</b></td>
			<td><b>City</b></td>
			<td><b>State</b></td>
			<td><b>County</b></td>
			<td><b>Delete Database</b></td>
		</tr>
HERE1;

$count = count($row);
for ($i = $count; $i > 0; $i--) {
	$tablename = $row[$i-1]->tablename;
	$location = unserialize($row[$i-1]->location);
	$city = $location['0'];
	$state = $location['1'];
	$county = $location['2'];
	$numchans = $row[$i-1]->numchans;
	$dbid = $row[$i-1]->tableid;
	
	$pagecontent .= <<<HERE2
	<tr>
		<td><a href="userdb.php?dbid=$dbid">$tablename</a></td>
		<td>$numchans</td>
		<td>$city</td>
		<td>$state</td>
		<td>$county</td>
		<td><a href='#'>Delete</a></td>
	</tr>
HERE2;
	
}

$pagecontent .= "</table>\n</center>";


$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($pagecontent);
$page->addFooter();
$page->printPage();




?>