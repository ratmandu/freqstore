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

if (!$login->checkLogin()) {
	header("Location: login.php");
	die();
}

$dbid = $_GET['dbid'];
$userid = $_SESSION['userid'];

// get info from database
$row = $sql->query("SELECT * FROM frequencies WHERE tableid = '$dbid'");

$tableuser = $row['0']->userid;
$tablename = $row['0']->tablename;
$location = unserialize($row['0']->location);
$lastedit = $row['0']->lastedit;
$numchans = $row['0']->numchans;
$freqs = $row['0']->frequency;
$alphatag = $row['0']->alphatag;
$description = $row['0']->description;
$sharestatus = $row['0']->public;
$sharecode = $row['0']->sharecode;

if ($userid == $tableuser) {
	$edits = true;
} else {
	$edits = false;
}


?>