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

// Site Info
define("SITE_NAME", "FreqStore");
 
// database info
define("DB_HOST", "hostname");
define("DB_USER", "username");
define("DB_PASS", "password");
define("DB_NAME", "database");

$database = mysql_pconnect(DB_HOST, DB_USER, DB_PASS) or trigger_error(mysql_error(), E_USER_ERROR);
mysql_select_db(DB_NAME) or die(mysql_error());


?>