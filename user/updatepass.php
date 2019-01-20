<?php require_once('../Connections/stusystem.php'); ?>
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("../Teacher/dw-upload.inc.php");?>
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

$MM_restrictGoTo = "../index.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateuserdata")) {
	
	if($_POST['password']==$_POST['repassword']){	
	
	
  $updateSQL = sprintf("UPDATE tbl_user SET password=%s WHERE username=%s",
                       GetSQLValueString(md5($_POST['password']), "text"),
                       GetSQLValueString($_POST['username'], "text"));

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

}else{
	echo "<script>alert('รหัสผิดพลาดโปรดแก้ไข')</script>";
	
}






//42320819($database_stusystem, $stusystem);
$query_usertype = "SELECT * FROM tbl_usertype";
$usertype = mysqli_query($stusystem, $query_usertype) or die(mysqli_error($stusystem));
$row_usertype = mysqli_fetch_assoc($usertype);
$totalRows_usertype = mysqli_num_rows($usertype);

$colname_user = "-1";
if (isset($_GET['userid'])) {
  $colname_user = $_GET['userid'];
}
//42320819($database_stusystem, $stusystem);
$query_user = sprintf("SELECT * FROM tbl_user WHERE userid = %s", GetSQLValueString($colname_user, "int"));
$user = mysqli_query($stusystem, $query_user) or die(mysqli_error($stusystem));
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ปรับปรุงข้อมูลสมาชิก</title>
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
<script
  src="https://code.jquery.com/jquery-1.10.2.js"
  integrity="sha256-it5nQKHTz+34HijZJQkpNBIHsjpV8b6QzMJs9tmOBSo="
  crossorigin="anonymous"></script>

<!-- นำเข้า Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
	<!-- นำเข้า Select2 CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
 
</head>

<body action="<?php echo $editFormAction; ?>">

<?php include ("../head.php");?>

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="updateuserdata" id="updateuserdata">
<div class="container">
 <a href="index.php" class="btn btn-primary">กลับไปหน้าข้อมูลสมาชิก</a> 
 <h2 style="text-align:center;">ปรับปรุงข้อมูลสมาชิก</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>ชื่อผู้ใช้ระบบ</td>
        <td><input name="username" type="text" required="required" class="form-control" id="username" placeholder="ใส่ Username ที่ต้องการ" value="<?php echo $row_user['username']; ?>" readonly ></td>
      </tr>
      
       <tr>
        <td>รหัสผ่านเดิม</td>
        <td><input name="oldpassword" type="password" required="required" class="form-control" id="oldpassword" placeholder="รหัสผ่านเดิม" ></td>
      </tr>
      <tr>
      <td>รหัสผ่านใหม่</td>
      <td><input name="password" type="password" required="required" class="form-control" id="password" placeholder="รหัสผ่านใหม่" ></td>
      </tr>
      <tr>
      <td>ยืนยัน รหัสผ่านใหม่</td>
      <td><input name="repassword" type="password" required="required" class="form-control" id="repassword" placeholder="ยืนยันรหัสผ่านใหม่" ></td>
      </tr>
       
     
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลสมาชิก" class="btn btn-success" ><input name="userid" type="hidden" id="userid" value="<?php echo $row_user['userid']; ?>"></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertteacherdata">
<input type="hidden" name="MM_update" value="updateuserdata">
</form>


</body>

</html>
<?php
mysqli_free_result($usertype);

mysqli_free_result($user);
?>
