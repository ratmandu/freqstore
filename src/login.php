<?php
/*
 * FreqStore - login.php
 * Copyright (C) 2008 Justin Richards
 * Author - Justin Richards <ratmandu@gmail.com>
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

// check if login form was submitted
if (isset($_POST['login'])) {
	$username = sanitizeSQL($_POST['user']);
	$password = md5(sanitizeSQL($_POST['pass']));
	
	// Get user from database
	$query = "SELECT * FROM users WHERE username = '$user'";
	$query = mysql_query($query);
	
	// verify that only one result matched
	if (mysql_num_rows($query) != "1") {
		die("The username you entered was not found. Please go back and check your spelling");
	}
}
















?>