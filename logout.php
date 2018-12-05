<?php include ("Connections/stusystem.php");?>
<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
  $sql = "INSERT INTO tbl_userlog (username,useraction) 
		VALUES ('".$_SESSION['MM_Username']."','LOG OUT')";
	 $Result1 = mysqli_query($stusystem, $sql) or die(mysqli_error($stusystem));
}

$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;

unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
