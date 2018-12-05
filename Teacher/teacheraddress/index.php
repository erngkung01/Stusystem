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

$colname_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacher = sprintf("SELECT a.*,b.* FROM tbl_teacher as a, tbl_prefix as b WHERE a.TeacherID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);

$colname_teacheraddress = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacheraddress = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacheraddress = sprintf("SELECT a.*,b.*,c.*,e.* FROM tbl_teacheraddress as a, province as b, amphur as c, district as e WHERE a.TeacherID = %s and a.PROVINCE_ID=b.PROVINCE_ID and a.AMPHUR_ID=c.AMPHUR_ID and a.DISTRICT_ID=e.DISTRICT_ID", GetSQLValueString($colname_teacheraddress, "text"));
$teacheraddress = mysqli_query($stusystem, $query_teacheraddress) or die(mysqli_error($stusystem));
$row_teacheraddress = mysqli_fetch_assoc($teacheraddress);
$totalRows_teacheraddress = mysqli_num_rows($teacheraddress);

$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.* FROM tbl_student as a, tbl_prefix as b,tbl_gender as c,tbl_blger as d,tbl_religion as e,tbl_group as f,tbl_studentstatus as g,tbl_classlevel as h WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode and a.GenderCode=c.GenderCode and a.blgerid=d.blgerid and a.Religionid=e.Religionid and a.groupid=f.groupid and a.statusid=g.statusid and f.classlevelid=h.classlevelid", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการข้อมูลที่อยู่อาจารย์</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active=11;
?>

<?php include ("../../head.php");?>
<!---เมนูหน้าข้อมูลนักเรียน--->
<?php include ("../teacher-menu.php");?>
<!--จบเมนูหน้าข้อมูลนักเรียน-->

<div class="col-sm-7">
<h1 style="text-align:center;">ข้อมูลที่อยู่อาจารย์</h1>
<h3 style="text-align:center;"><?php echo $row_teacher['PrefixName']; ?><?php echo $row_teacher['name']; ?> <?php echo $row_teacher['surname']; ?></h3>
<?php if ($totalRows_teacheraddress > 0) { // Show if recordset not empty ?>
  <div style="text-align:right">
    
    <a href="update.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-warning">แก้ไขข้อมูลที่อยู่อาจารย์</a>
    <a href="delete.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
  </div>
  <br>
  
  <table class="table table-hover">
    
    <tbody>
      <tr>
        <td>บ้านเลขที่</td>
        <td><?php echo $row_teacheraddress['Homeid']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>หมู่</td>
        <td><?php echo $row_teacheraddress['moo']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>ถนน</td>
        <td><?php echo $row_teacheraddress['street']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>ตำบล</td>
        <td><?php echo $row_teacheraddress['DISTRICT_NAME']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>อำเภอ</td>
        <td><?php echo $row_teacheraddress['AMPHUR_NAME']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>จังหวัด</td>
        <td><?php echo $row_teacheraddress['PROVINCE_NAME']; ?>&nbsp;</td>
        </tr>
      <tr>
        <td>รหัสไปรษณีย์</td>
        <td><?php echo $row_teacheraddress['ZipCode']; ?>&nbsp;</td>
        </tr>
      
      </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_teacheraddress == 0) { // Show if recordset empty ?>
  <table class="table table-hover">
    <tbody>
      <tr>
        <td>ไม่มีข้อมูลที่อยู่นักเรียน</td>
        <td><a href="insert-not.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-danger">เพิ่มข้อมูลที่อยู่อาจารย์</a></td>
        </tr>
      
      </tbody>
  </table>
  <?php } // Show if recordset empty ?>
</div>

<div class="col-sm-2">

<?php include ("../teacher-menu2.php");?>

</div>

</body>
</html>
<?php
mysqli_free_result($teacher);

mysqli_free_result($teacheraddress);
?>
