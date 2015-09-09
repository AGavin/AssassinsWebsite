<?php
include('config.php');
$link = mysql_connect($mysql_host, $mysql_user, $mysql_password)
    or die("Could not connect to database host");
mysql_select_db($database) or die("Could not select database");
?>
