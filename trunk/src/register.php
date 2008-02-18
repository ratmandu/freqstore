<?php
/*
 * FreqStore - register.php
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
	 // process the registration
	 $sql = new sql();
	 
	 $username = $_POST['username'];
	 $email = $_POST['email'];
	 $pass1 = $_POST['pass1'];
	 $pass2 = $_POST['pass2'];
	 
	 // make sure all fields were filled in
	 if (!($username) || !($pass1) || !($pass2) || !($email)) {
	 	header("Location: register.php?error=filled");
	 	die();
	 }
	 
	 $username = $sql->sanitize($username);
	 $email = $sql->sanitize($email);
	 $pass1 = md5($pass1);
	 $pass2 = md5($pass2);
	 
	 
	 
	 // make sure that the passwords match
	 if (!($pass1 == $pass2)) {
	 	header("Location: register.php?error=pass");
	 	die();
	 }
	 
	 // check to see if user already exists
	 $result = $sql->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
	 if ($result) {
	 	header("Location: register.php?error=used");
	 	die();
	 }
	 
	 $fields = "userid, username, password, email";
	 $values = "NULL, '$username', '$pass1', '$email'";
	 
	 if ($sql->insert("INSERT INTO users(userid, username, password, email) VALUES(NULL, '$username', '$pass1', '$email')")) {
		header("Location: index.php?thanks=1");
		die();
	} else {
		//header("Location: register.php?error=badinsert");
		echo $fields . " " . $values;
		die();
	}
	 
	 
}
 
$page = new buildPage("Register");

 $pagecontent = <<<END1
<center>
	<form action="register.php" method="post">
		<table>
			<tr>
				<td>Username: </td>
				<td><input type="text" name="username" size="15"></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type="password" name="pass1" size="15"></td>
			</tr>
			<tr>
				<td>Again: </td>
				<td><input type="password" name="pass2" size="15"></td>
			</tr>
			<tr>
				<td>Email: </td>
				<td><input type="text" name="email" size="15"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
	</form>
</center>
END1;

if (isset($_GET['error'])) {
	switch ($_GET['error']) {
		case "filled":
			$page->addError("Please make sure all fields are filled.");
			break;
		
		case "pass":
			$page->addError("The passwords you entered did not match. Please check them and try again");
			break;
			
		case "used":
			$page->addError("The username or email you entered is already in use, Please choose another");
			break;
			
		case "badinsert":
			$page->addError("There was an error inserting the user into the database");
			break;
	}
}

$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($pagecontent);
$page->addFooter();
$page->printPage();

 


?>