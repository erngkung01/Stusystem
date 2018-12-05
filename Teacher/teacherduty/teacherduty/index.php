<?php require_once('../../../Connections/stusystem.php'); ?>
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

$MM_restrictGoTo = "../../../index.php";
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
$query_teacher = sprintf("SELECT a.*,b.* FROM tbl_teacher as a, tbl_prefix as b WHERE TeacherID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $totalRows_teacher = $_GET['TeacherID'];
}


$colname_dutyyear = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_dutyyear = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_dutyyear = sprintf("SELECT a.*,b.* FROM tbl_dutyyear as a , tbl_academicyears as b WHERE a.TeacherID = %s and a.AcademicYearsID=b.AcademicYearsID", GetSQLValueString($colname_dutyyear, "text"));
$dutyyear = mysqli_query($stusystem, $query_dutyyear) or die(mysqli_error($stusystem));
$row_dutyyear = mysqli_fetch_assoc($dutyyear);
$totalRows_dutyyear = mysqli_num_rows($dutyyear);

$colname_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacher = sprintf("SELECT a.*,b.*  FROM tbl_teacher as a, tbl_prefix as b  WHERE a.TeacherID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);

$colname_duty = "-1";
if (isset($_GET['dutyyearID'])) {
  $colname_duty = $_GET['dutyyearID'];
}
//42320819($database_stusystem, $stusystem);
$query_duty = sprintf("SELECT * FROM tbl_duty WHERE dutyyearID = %s", GetSQLValueString($colname_duty, "int"));
$duty = mysqli_query($stusystem, $query_duty) or die(mysqli_error($stusystem));
$row_duty = mysqli_fetch_assoc($duty);
$totalRows_duty = mysqli_num_rows($duty);




$currentPage = $_SERVER["PHP_SELF"];

$queryString_studentaward1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_studentaward1") == false && 
        stristr($param, "totalRows_studentaward1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_studentaward1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_studentaward1 = sprintf("&totalRows_studentaward1=%d%s", $totalRows_studentaward1, $queryString_studentaward1);

$queryString_studentaward2 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_studentaward2") == false && 
        stristr($param, "totalRows_studentaward2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_studentaward2 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_studentaward2 = sprintf("&totalRows_studentaward2=%d%s", $totalRows_studentaward2, $queryString_studentaward2);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลหน้าที่อาจารย์</title>
<link rel="stylesheet" href="../../../css/bootstrap.css">
<script type="text/javascript" src="../../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 12;
?>

  <?php include ("../../../head.php");?>
  <!---เมนูหน้าข้อมูลอาจารย์--->
<?php include ("../../teacher-menu.php");?>
  <!--จบเมนูหน้าข้อมูลอาจารย์-->
  
  <div class="col-sm-7">
    <h1 style="text-align:center;">ระบบจัดการข้อมูลหน้าที่อาจารย์</h1>
    <h3 style="text-align:center;"><?php echo $row_teacher['PrefixName']; ?><?php echo $row_teacher['name']; ?> <?php echo $row_teacher['surname']; ?> </h3> <div style="text-align:right">
    
   <a href="insert.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>&dutyyearID=<?php echo $row_dutyyear['dutyyearID']; ?>" class="btn btn-success">เพิ่มหน้าที่อาจารย์</a>
    
    </div>
   
  <h3 style="text-align:left;">
    หน้าที่อาจารย์ ประจำปีการศึกษา <?php echo $row_dutyyear['AcademicYears']; ?>
  </h3>
    <br>
    <?php if ($totalRows_duty > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>หน้าที่อาจารย์</th>
        
        <th>จัดการข้อมูล</th>
        <th>ลบ</th>
        </tr>
      </thead>
    
    <tbody>
      <?php do { ?>
      <tr>
        <td><?php echo $row_duty['dutyname']; ?></td>
        
        <td><a href="update.php?dutyID=<?php echo $row_duty['dutyID']; ?>&TeacherID=<?php echo $row_teacher['TeacherID']; ?>&dutyyearID=<?php echo $row_dutyyear['dutyyearID']; ?>" class="btn btn-warning">แก้ไข</a></td>
        <td><a href="delete.php?dutyID=<?php echo $row_duty['dutyID']; ?>&TeacherID=<?php echo $row_teacher['TeacherID']; ?>&dutyyearID=<?php echo $row_dutyyear['dutyyearID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        
        </tr>
      <?php } while ($row_duty = mysqli_fetch_assoc($duty)); ?>
      </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_duty == 0) { // Show if recordset empty ?>
    ไม่มีข้อมูล
  <?php } // Show if recordset empty ?>
  </div>
  
  <div class="col-sm-2">
   
   <?php include ("../../teacher-menu2.php");?>
    
  </div>
  
  
  
</body>
</html>
<?php
mysqli_free_result($teacher);

mysqli_free_result($duty);

mysqli_free_result($dutyyear);
?>
