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

$currentPage = $_SERVER["PHP_SELF"];

$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.* FROM tbl_student as a , tbl_prefix as b WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

$colname_studentaward = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentaward = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaward = sprintf("SELECT a.*,b.*,c.* FROM tbl_award as a left join  tbl_awardtype as b on a.AwardTypeID=b.AwardTypeID left join tbl_academicyears as c on a.AwardYear=c.AcademicYearsID WHERE a.studentID = %s and  (a.AwardTypeID=b.AwardTypeID or b.AwardTypeID is null  ) and  (a.AwardYear=c.AcademicYearsID or c.AcademicYearsID is null ) ORDER BY a.AwardYear DESC", GetSQLValueString($colname_studentaward, "text"));
$studentaward = mysqli_query($stusystem, $query_studentaward) or die(mysqli_error($stusystem));
$row_studentaward = mysqli_fetch_assoc($studentaward);
$totalRows_studentaward = mysqli_num_rows($studentaward);

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
<title>ระบบจัดการข้อมูลผลงานนักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 8;
?>

  <?php include ("../../head.php");?>
  <!---เมนูหน้าข้อมูลนักเรียน--->
  <?php include ("../student-menu.php");?>
  <!--จบเมนูหน้าข้อมูลนักเรียน-->
  
  <div class="col-sm-7">
    <h1 style="text-align:center;">ระบบจัดการข้อมูลผลงาน/รางวัล นักเรียน</h1>
    <h3 style="text-align:center;"><?php echo $row_studentdata['PrefixName']; ?>  <?php echo $row_studentdata['student_name']; ?> <?php echo $row_studentdata['student_surname']; ?></h3> <div style="text-align:right">
    
    
      <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
    
    <a href="insert.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-success">เพิ่มผลงาน/รางวัล</a>
    
    <?php } ?>
    
    </div>
    
  <h3 style="text-align:left;">
    ผลงาน/รางวัล
  </h3>
    <br>
    <?php if ($totalRows_studentaward > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ประเภทผลงาน/รางวัล</th>
        <th>ชื่อผลงาน</th>
        <th>ปีการศึกษา</th>
        <th>จัดการข้อมูล</th>
          <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <th>ลบ</th>
        <?php } ?>
        </tr>
      </thead>
    
    <tbody>
      <?php do { ?>
      <tr>
        <td><?php echo $row_studentaward['AwardTypeName']; ?></td>
        <td><?php echo $row_studentaward['AwardName']; ?></td>
        <td><?php echo $row_studentaward['AcademicYears']; ?></td>
        <td><a href="view.php?AwardID=<?php echo $row_studentaward['AwardID']; ?>&studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-primary">จัดการข้อมูล</a></td>
          <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <td><a href="delete.php?AwardID=<?php echo $row_studentaward['AwardID']; ?>&studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        
        <?php } ?>
        
        </tr>
      <?php } while ($row_studentaward = mysqli_fetch_assoc($studentaward)); ?>
      </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_studentaward == 0) { // Show if recordset empty ?>
   
   
    <br>
        <table class="table table-hover">
    <tbody>
      <tr>
        <td style="text-align:center;">ไม่มีข้อมูล</td>
       
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

mysqli_free_result($studentaward);
?>
