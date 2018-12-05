<?php require_once('../../Connections/stusystem.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "111,222,333";
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

$MM_restrictGoTo = "../../index.php";
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
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("dw-upload.inc.php");?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "img")) {
	if($_FILES['fileField']['name']!=""){
  $updateSQL = sprintf("UPDATE tbl_student SET student_img=%s WHERE studentID=%s",
                       GetSQLValueString(dwUpload($_FILES['fileField']), "text"),
                       GetSQLValueString($_POST['hiddenField'], "text"));
					   
	}else{
		header("Location:../index.php");
		
	}

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_studentid = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentid = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentid = sprintf("SELECT * FROM tbl_student WHERE studentID = %s", GetSQLValueString($colname_studentid, "text"));
$studentid = mysqli_query($stusystem, $query_studentid) or die(mysqli_error($stusystem));
$row_studentid = mysqli_fetch_assoc($studentid);
$totalRows_studentid = mysqli_num_rows($studentid);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>อัปโหลดรูปภาพนักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script src="../../js/jquery-1.11.2.min.js"></script>
<script src="../../js/bootstrap.js"></script>
</head>

<body>
<?php include ("../../head.php");?>
<div class="col-sm-9">
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="img" id="img">
<table class="table table-hover">
    <thead>
      <tr>
        <th colspan="3" style="text-align: center"><blockquote>
          <p style="text-align: center">แก้ไขรูปภาพนักเรียน</p>
        </blockquote></th>
      </tr>
    </thead>
    <tbody>
    <tr>
        <td>รูปภาพเดิม</td>
        <td> <img src="images/<?php echo $row_studentid['student_img']; ?>" id="" alt="" height="150" width="150"></td>
    
      <tr>
        <td>รูปภาพนักเรียนใหม่</td>
        <td> <input type="file" name="fileField" id="fileField"></td>
       
      </tr>
      <tr>
      <td style="text-align: right"> <input type="submit" name="submit" id="submit" value="อัปโหลดรูปภาพนักเรียน" class="btn btn-success"><input type="hidden" name="hiddenField" id="hiddenField" value="<?php echo $row_studentid['studentID']; ?>"><input type="hidden" name="MM_update" value="img"></td>
      
      <td></td>
      </tr>
    </tbody>
   
  </table>
   
</form>
<p style="text-align: left"><a href="index.php?studentID=<?php echo $row_studentid['studentID']; ?>" class="btn btn-danger">กลับไปหน้าข้อมูลนักเรียน</a></p>
</div>
<div class="col-sm-3">

</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($studentid);
?>
