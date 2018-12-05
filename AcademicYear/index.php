<?php require_once('../Connections/stusystem.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "111,222";
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_AcademicYear = 10;
$pageNum_AcademicYear = 0;
if (isset($_GET['pageNum_AcademicYear'])) {
  $pageNum_AcademicYear = $_GET['pageNum_AcademicYear'];
}
$startRow_AcademicYear = $pageNum_AcademicYear * $maxRows_AcademicYear;

//42320819($database_stusystem, $stusystem);
$query_AcademicYear = "SELECT * FROM tbl_academicyears ORDER BY AcademicYears DESC";
$query_limit_AcademicYear = sprintf("%s LIMIT %d, %d", $query_AcademicYear, $startRow_AcademicYear, $maxRows_AcademicYear);
$AcademicYear = mysqli_query($stusystem, $query_limit_AcademicYear) or die(mysqli_error($stusystem));
$row_AcademicYear = mysqli_fetch_assoc($AcademicYear);

if (isset($_GET['totalRows_AcademicYear'])) {
  $totalRows_AcademicYear = $_GET['totalRows_AcademicYear'];
} else {
  $all_AcademicYear = mysqli_query($stusystem, $query_AcademicYear);
  $totalRows_AcademicYear = mysqli_num_rows($all_AcademicYear);
}
$totalPages_AcademicYear = ceil($totalRows_AcademicYear/$maxRows_AcademicYear)-1;

$queryString_AcademicYear = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_AcademicYear") == false && 
        stristr($param, "totalRows_AcademicYear") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_AcademicYear = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_AcademicYear = sprintf("&totalRows_AcademicYear=%d%s", $totalRows_AcademicYear, $queryString_AcademicYear);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการปีการศึกษา</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php $page_active= 7 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการปีการศึกษา</h1>
  <a href="insert.php" class="btn btn-success">เพิ่มปีการศึกษา</a><br>
  <?php if ($totalRows_AcademicYear > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        
        <th>ปีการศึกษา</th>
        <th>แก้ไข</th>
        
        <th>ลบ</th>
        </tr>
      </thead>
    <tbody>
      <?php do { ?>
      <tr>
        <td><?php echo $row_AcademicYear['AcademicYears']; ?>&nbsp;</td>
        <td><a href="update.php?AcademicYearsID=<?php echo $row_AcademicYear['AcademicYearsID']; ?>" class="btn btn-warning">แก้ไข</a></td>
        <td><a href="delete.php?AcademicYearsID=<?php echo $row_AcademicYear['AcademicYearsID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        </tr>
      <?php } while ($row_AcademicYear = mysqli_fetch_assoc($AcademicYear)); ?>
      </tbody>
  </table>
    
    <ul class="pagination">
      <li><a href="<?php printf("%s?pageNum_AcademicYear=%d%s", $currentPage, 0, $queryString_AcademicYear); ?>">หน้าแรก</a></li>
      <li><a href="<?php printf("%s?pageNum_AcademicYear=%d%s", $currentPage, max(0, $pageNum_AcademicYear - 1), $queryString_AcademicYear); ?>">หน้าก่อนหน้า</a></li>
      <?php $pid = $_GET["pageNum_AcademicYear"];   ?>
      <?php  
   for($dw_i=0;$dw_i<=$totalPages_AcademicYear;$dw_i++){  ?>
      <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="?pageNum_AcademicYear=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li>
      <?php
   }
   ?>
      <li><a href="<?php printf("%s?pageNum_AcademicYear=%d%s", $currentPage, min($totalPages_AcademicYear, $pageNum_AcademicYear + 1), $queryString_AcademicYear); ?>">หน้าต่อไป</a></li>
      <li><a href="<?php printf("%s?pageNum_AcademicYear=%d%s", $currentPage, $totalPages_AcademicYear, $queryString_AcademicYear); ?>">หน้าสุดท้าย</a></li>
    </ul>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_AcademicYear == 0) { // Show if recordset empty ?>
      ไม่พบข้อมูลอาจารย์
  <?php } // Show if recordset empty ?>
</div>
</body>
</html>
<?php
mysqli_free_result($AcademicYear);
?>
