<?php
/*
 * FreqStore
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
 
include("includes/config.php");
include("includes/functions.php");

$login = new login();


$page = new buildPage("Home");

if (isset($_GET['thanks'])) {
	$page->addMessage("Registration Complete! <br> Thanks!");
}

if (isset($_GET['loggedout'])) {
	$page->addMessage("You were logged out sucessfully!");
}

$pagecontent = <<<HERE1
HERE1;

$page->addLeftMenu();
$page->showErrors();
$page->showMessages();
$page->addContent($pagecontent);
$page->addFooter();
$page->printPage();


?>