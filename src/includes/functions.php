<?php
/*
 * FreqStore - functions.php
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


/**
 * Creates page, based on a template and the pages seperate parts.
 * 
 */
class buildPage {
	/**
	 * Anything displayed on the page
	 *
	 * @var string
	 */
	public $pagetext; 
	
	/**
	 * Array of errors, if any.
	 *
	 * @var string
	 */
	public $errors = array(); 
	
	/**
	 * Class Contructor. 
	 * 
	 * Blanks out the pagetext variable and starts the html page.
	 *
	 * @param string $title Page title
	 */
	function buildPage($title) {
		$this->pagetext = NULL;
		$this->beginPage($title);
	}
	
	/**
	 * Starts the html page off, and sets the page title
	 *
	 * @param string $title title of page created
	 */
	function beginPage($title) {
		$this->pagetext = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link rel='stylesheet' href='includes/style.css' media='screen' />
<title>$title - ".SITE_NAME."</title>
</head>
<body>
	<div id='container'>
	<div class='topbox'></div>\n";	
	}
	
	/**
	 * Adds left hand side navigation menu
	 *
	 * @param bool $loggedin decides weather or not to show features for logged in users
	 */
	function addLeftMenu($loggedin = false) {
		$this->pagetext .= "	<div class='leftmenu'>\n";
		$this->pagetext .= "
	<li><a href='#'>Home</a></li>
	<li><a href='#'>Link 1</a></li>
	<li><a href='#'>Link 2</a></li>
	<li><a href='#'>Link 3</a></li>
	<li><a href='#'>Link 4</a></li>";
		if ($loggedin) {
			$this->pagetext .= "User is Logged In";
		}
		$this->pagetext .= "\n	</div>\n";
	}
	
	/**
	 * Adds an error to be shown to the user
	 *
	 * @param string $errortext What text to show to the user.
	 */
	function addError($errortext) {
		$errorary = $this->errors;
		$errorcount = count($errorary);
		$errorcount++;
		$this->errors[$errorcount] .= $errortext;
	}
	
	/**
	 * Prints the errors out to the screen.
	 *
	 */
	function showErrors() {
		$errorcount = count($this->errors);
		if ($errorcount >= 1) {
			for ($i = 1; $i <= $errorcount; $i++) {
				$this->pagetext .= "\n	<div class='errorbox'>\n";
				$this->pagetext .= $this->errors[$i];
				$this->pagetext .= "\n	</div>\n";
			}
		}
	}
	
	/**
	 * Adds a content box filled with specified content
	 *
	 * @param string $content Content to go in the box for the user.
	 */
	function addContent($content) {
		$this->pagetext .= "	<div class='contentbox'>\n";
		$this->pagetext .= $content;
		$this->pagetext .= "\n	</div>\n";
	}
	
	/**
	 * Inserts the page footer, thus ending off the page.
	 *
	 */
	function addFooter() {
		$this->pagetext .= "	</div>\n";
		$this->pagetext .= "</body>\n";
		$this->pagetext .= "</html>\n";
	}
	
	/**
	 * Displays the entire page to the user
	 *
	 */
	function printPage() {
		echo $this->pagetext;
	}
	
	
}

/**
 * MySQL communication stuff
 *
 */
class sql {
	/**
	 * database connection resource
	 *
	 * @var mixed
	 */
	public $dbc;
	/**
	 * holds query results
	 *
	 * @var string
	 */
	public $result;
	
	/**
	 * Class constructor.
	 * 
	 * Connects to the database, then calls select which selects the right database.
	 */
	function sql() {
		$this->dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		
		if (! $this->dbc) {
			return 0;
		}
		
		$this->select(DB_NAME);
	}
	
	/**
	 * Selects database
	 *
	 * @param string $db Name of database to select
	 * @return bool Returns false (zero) is there was a problem selecting the database.
	 */
	function select($db) {
		if (! mysql_select_db($db, $this->dbc)) {
			return 0;
		}
	}
	
	/**
	 * Executes a query on the database
	 * 
	 * Use it like so:
	 * <code>
	 * <?php
	 * $sql = new sql();
	 * $row = $sql->query("SELECT * FROM users");
	 * // will show the username of the 4th result in the database
	 * echo $row[4]->username;
	 * ?>
	 *
	 * @param string $query Query to execute on the database
	 * @return mixed returns array/objects as $newrow[rownumber]->fieldname
	 */
	function query($query) {
		$this->result = mysql_query($query, $this->dbc);
		
		if ($this->result) {
			$i = 0;
			while ($row = mysql_fetch_object($this->result)) {
				$newRow[$i] = $row;
				$i++;
			}
			
			mysql_free_result($this->result);
			return $newRow;
		}
	}
	
	/**
	 * Sanitizes information to pass in an SQL query to help prevent SQL injection
	 *
	 * @param string $text String to sanitize
	 * @return string sanitized text
	 */
	function sanitize($text) {
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
	
		if (function_exists("mysql_real_escape_string")) {
			$text = mysql_real_escape_string($text);
		} else {
			$text = addslashes($text);
		}
		
		return $text;
	}

}



?>