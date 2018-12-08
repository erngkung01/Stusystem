<?php require_once('Connections/stusystem.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "111,222,333,444";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
global $stusystem;
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($stusystem, $theValue) : mysqli_escape_string($stusystem, $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
      case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "password":
    $theValue = ($theValue != "") ? "'" . md5($theValue) . "'" : "NULL";
    break; 
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

//42320819($database_stusystem, $stusystem);
$query_Recordset1 = "SELECT * FROM tbl_user";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($stusystem, $query_limit_Recordset1) or die(mysqli_error($stusystem));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysqli_query($stusystem, $query_Recordset1);
  $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบบริหารจัดการข้อมูลนักเรียนโรงเรียนอินทารามวิทยา</title>
<link rel="stylesheet" href="css/bootstrap.css">
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>

</head>

<body>
<!-----head------>
<?php include("head.php"); echo "<br>"; ?>
<!-------end head-------->
<div class="container">
<div class="col-sm-4">
<div class="panel panel-success">
      <div class="panel-heading"><h3><a href="student/index.php" style="color:#043D03;"><i class="glyphicon glyphicon-user"></i>  ระบบจัดการข้อมูลนักเรียน</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="student/index.php" style="color:#043D03;">คลิกที่นี่</a></div>
    </div>
</div>
<div class="col-sm-4">
<div class="panel panel-info">
      <div class="panel-heading"><h3><a href="Teacher/index.php" style="color:#070F90"><i class="glyphicon glyphicon-book"></i>  ระบบจัดการข้อมูลอาจารย์</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="Teacher/index.php" style="color:#070F90;">คลิกที่นี่</a></div>
    </div>

</div>
<div class="col-sm-4">
<div class="panel panel-primary">
      <div class="panel-heading"><h3><a href="Group/index.php" style="color:#FFFFFF"><i class="glyphicon glyphicon-th"></i>  ระบบจัดการข้อมูลห้องเรียน</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="Group/index.php" style="color:#0100A7">คลิกที่นี่</a></div>
    </div>

</div>
<?php if($_SESSION['MM_UserGroup']==111){ ?>
<div class="col-sm-4">

<div class="panel panel-warning">
      <div class="panel-heading"><h3><a href="user/index.php" style="color:#A67A0E"><i class="glyphicon glyphicon-th-list"></i>  ระบบจัดการข้อมูลสมาชิก</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="user/index.php" style="color:#A67A0E">คลิกที่นี่</a></div>
    </div>

</div>
<?php } ?>
<?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
<div class="col-sm-4">

<div class="panel panel-danger">
      <div class="panel-heading"><h3><a href="AcademicYear/index.php" style="color:#000B8B;""><i class="glyphicon glyphicon-th"></i>  ระบบจัดการข้อมูลปีการศึกษา</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="AcademicYear/index.php" style="color:#0100A7">คลิกที่นี่</a></div>
    </div>

</div>
<?php } ?>
<?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
<div class="col-sm-4">
<div class="panel panel-default">
      <div class="panel-heading"><h3><a href="Position/index.php" style="color:#000B8B;""><i class="glyphicon glyphicon-glass"></i>  ระบบจัดการข้อมูลตำแหน่ง</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="Position/index.php" style="color:#0100A7">คลิกที่นี่</a></div>
    </div>
</div>
<?php } ?>

<div class="col-sm-4">
<div class="panel panel-default">
      <div class="panel-heading"><h3><a href="chart/index.php" style="color:#000B8B;""><i class="glyphicon glyphicon-equalizer"></i>  ระบบรายงานข้อมูลนักเรียน</a></h3></div>
      <div class="panel-body" style="text-align:right;"><a href="chart/index.php"  style="color:#0100A7">คลิกที่นี่</a></div>
    </div>
</div>



 
 </div>
</body>
</html>
<?php
mysqli_free_result($Recordset1);
?>
