<?php require_once('../Connections/stusystem.php'); ?>
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

$colname_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacher = sprintf("SELECT a.*,b.*,c.*,d.* FROM tbl_teacher as a , tbl_prefix as b,tbl_gender as c, tbl_position as d WHERE a.TeacherID = %s and a.PrefixCode=b.PrefixCode and a.GenderCode=c.GenderCode and a.PositionID=d.PositionID", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);

$colname_teacherdata = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacherdata = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacherdata = sprintf("SELECT a.*,b.* FROM tbl_teacher as a, tbl_prefix as b WHERE a.TeacherID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_teacherdata, "text"));
$teacherdata = mysqli_query($stusystem, $query_teacherdata) or die(mysqli_error($stusystem));
$row_teacherdata = mysqli_fetch_assoc($teacherdata);
$totalRows_teacherdata = mysqli_num_rows($teacherdata);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการข้อมูลอาจารย์</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<script type="text/javascript" src="../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 4;
?>

<?php include ("../head.php");?>
<?php $page_active= 9 ;?>
<!---เมนูครู-->
<?php include ("teacher-menu.php");?>
<!---จบเมนูครู-->
<div class="col-sm-6">
<h1 style="text-align:center;">ข้อมูลอาจารย์</h1>
<h3 style="text-align:center;"><?php echo $row_teacherdata['PrefixName']; ?><?php echo $row_teacherdata['name']; ?>  <?php echo $row_teacherdata['surname']; ?></h3> 
<div style="text-align:left;"><a href="index.php" class="btn btn-primary">กลับหน้าข้อมูลอาจารย์</a></div>
<div style="text-align:right"><a href="update.php?TeacherID=<?php echo $row_teacherdata['TeacherID']; ?>" class="btn btn-info">แก้ไขข้อมูลอาจารย์</a>  <a href="delete.php?TeacherID=<?php echo $row_teacherdata['TeacherID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a></div>
<br>

  <table class="table table-hover">
    <tbody>
      <tr>
        <td>รหัสอาจารย์</td>
        <td><?php echo $row_teacher['TeacherID']; ?>&nbsp;</td>
      </tr>
      <tr>
        <td>รหัสประชาชน</td>
        <td><?php echo $row_teacher['PersonID']; ?></td>
      </tr>
      <tr>
        <td>ชื่อ-นามสกุล </td>
        <td><?php echo $row_teacher['PrefixName']; ?><?php echo $row_teacher['name']; ?>  <?php echo $row_teacher['surname']; ?>&nbsp;</td>
      </tr>
     
      <tr>
        <td>เพศ</td>
        <td><?php echo $row_teacher['GenderName']; ?>&nbsp;</td>
      </tr>
      <tr>
      <td>วันเริ่มทำงาน</td>
      <td>
	  <?php
		if($row_teacher['WorkDate']!=''){
		$date = $row_teacher['WorkDate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."-".$date2."-".$date1;
		echo $dateval;
		}
		
		 ?>
         </td>
      </tr>
      <tr>
      <td>ตำแหน่ง</td>
      <td><?php echo $row_teacher['Positionname']; ?></td>
      </tr>
      <tr>
        <td>เบอร์โทรศัพท์ </td>
        <td><?php echo $row_teacher['tel']; ?></td>
      </tr>
      <tr>
        <td>E-mail</td>
        <td><?php echo $row_teacher['email']; ?></td>
      </tr>
    </tbody>
  </table>
 

</div>

<div class="col-sm-3">

<?php include ("teacher-menu2.php");?>

</div>

</body>
</html>
<?php
mysqli_free_result($teacher);

mysqli_free_result($teacherdata);
?>
