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


if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.* FROM tbl_student as a, tbl_prefix as b WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode ", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

$colname_studentaddress = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentaddress = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaddress = sprintf("SELECT a.*,b.*,c.*,d.* FROM tbl_address as a left join  province as b on a.PROVINCE_ID=b.PROVINCE_ID left join  district as c on a.DISTRICT_ID=c.DISTRICT_ID left join  amphur as d on a.AMPHUR_ID=d.AMPHUR_ID WHERE a.studentID = %s and  (a.PROVINCE_ID=b.PROVINCE_ID or b.PROVINCE_ID  is null  ) and  (a.DISTRICT_ID=c.DISTRICT_ID or c.DISTRICT_ID is null  ) and  (a.AMPHUR_ID=d.AMPHUR_ID or d.AMPHUR_ID is null  )", GetSQLValueString($colname_studentaddress, "text"));
$studentaddress = mysqli_query($stusystem, $query_studentaddress) or die(mysqli_error($stusystem));
$row_studentaddress = mysqli_fetch_assoc($studentaddress);
$totalRows_studentaddress = mysqli_num_rows($studentaddress);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการข้อมูลที่อยู่นักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>


</head>

<body>
<?php
$page_active= 2;
?>

<?php include ("../../head.php");?>
<!---เมนูหน้าข้อมูลนักเรียน--->
<?php include ("../student-menu.php");?>
<!--จบเมนูหน้าข้อมูลนักเรียน-->

<div class="col-sm-7">
<h1 style="text-align:center;">ข้อมูลที่อยู่นักเรียน</h1>
<h3 style="text-align:center;"><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?>  <?php echo $row_studentdata['student_surname']; ?></h3>
<?php if ($totalRows_studentaddress > 0) { // Show if recordset not empty ?>
  <div style="text-align:right">
    
    <a href="update.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-warning">แก้ไขข้อมูลที่อยู่นักเรียน</a>
    <a href="../Studentdata/delete.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
  </div>
  <br>
  
  <table class="table table-hover">
    
    <tbody>
      <tr>
        <td>รหัสประจำบ้าน</td>
        <td><?php echo $row_studentaddress['Homeid']; ?></td>
        </tr>
      <tr>
        <td>บ้านเลขที่</td>
        <td><?php echo $row_studentaddress['HouseNo']; ?></td>
        </tr>
      <tr>
        <td>หมู่</td>
        <td><?php echo $row_studentaddress['moo']; ?></td>
        </tr>
      <tr>
        <td>ถนน</td>
        <td><?php echo $row_studentaddress['street']; ?></td>
        </tr>
      <tr>
        <td>ตำบล</td>
        <td><?php echo $row_studentaddress['DISTRICT_NAME']; ?></td>
        </tr>
      <tr>
        <td>อำเภอ</td>
        <td><?php echo $row_studentaddress['AMPHUR_NAME']; ?></td>
        </tr>
      <tr>
        <td>จังหวัด</td>
        <td><?php echo $row_studentaddress['PROVINCE_NAME']; ?></td>
        </tr>
      <tr>
        <td>รหัสไปรษณีย์</td>
        <td><?php echo $row_studentaddress['ZipCode']; ?></td>
        </tr>
        <tr>
        <td>แผนที่บ้านนักเรียน</td>
        <td><?php echo $row_studentaddress['stu_gps']; ?></td>
        </tr>
      
      </tbody>
  </table>
  
 
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_studentaddress == 0) { // Show if recordset empty ?>
  
  <table class="table table-hover">
    <tbody>
      <tr>
        <td>ไม่มีข้อมูลที่อยู่นักเรียน</td>
        <td><a href="insert-not.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger">เพิ่มข้อมูลที่อยู่นักเรียน</a></td>
      </tr>
      
    </tbody>
  </table>
  <?php } // Show if recordset empty ?>
</div>

<div class="col-sm-2">

<?php include ("../student-menu2.php");?>

</div>

</body>
</html>
<?php
mysqli_free_result($studentdata);

mysqli_free_result($studentaddress);
?>
