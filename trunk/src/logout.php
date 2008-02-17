<?php

include("includes/config.php");
include("includes/functions.php");

$login = new login();
$login->logout();
header("Location: index.php");
die();

?>