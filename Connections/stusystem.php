<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_stusystem = "";
$database_stusystem = "";
$username_stusystem = "";
$password_stusystem = "";
$stusystem = mysqli_connect($hostname_stusystem, $username_stusystem, $password_stusystem, $database_stusystem) or trigger_error(mysqli_connect_errno(),E_USER_ERROR); 
mysqli_set_charset($stusystem, "utf8");
?>