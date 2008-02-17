<?php
/*
 * FreqStore - login.php
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

if (isset($_POST['submit'])) {
	// Process the login.	
	$username = $_POST['user'];
	$password = $_POST['pass'];
	$password = sha1($password);
	
	$login = new login();
	if ($login->process($username, $password)) {
		header("Location: index.php");
	} else {
		header("Location: login.php?error=1");
	}
	die();
}


$pagecontent = <<<END1
<center>
	<form method='post' action='login.php'>
		
		<table>
			<tr>
				<td>Username:</td>
				<td><input type='text' name='user' size='15'></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type='password' name='pass' size='15'></td>
			</tr>
			<tr>
				<td><a href='register.php'> Register</a></td>
				<td><input type='submit' name='submit' value='Submit'></td>
			</tr>
		</table>
	</form>
</center>
END1;

$page = new buildPage("Login Page");

if (isset($_GET['error'])) {
	$page->addError("Invalid Login");
}

$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($pagecontent);
$page->addFooter();
$page->printPage();

?>