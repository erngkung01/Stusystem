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

$maxRows_position = 10;
$pageNum_position = 0;
if (isset($_GET['pageNum_position'])) {
  $pageNum_position = $_GET['pageNum_position'];
}
$startRow_position = $pageNum_position * $maxRows_position;

//42320819($database_stusystem, $stusystem);
$query_position = "SELECT * FROM tbl_position";
$query_limit_position = sprintf("%s LIMIT %d, %d", $query_position, $startRow_position, $maxRows_position);
$position = mysqli_query($stusystem, $query_limit_position) or die(mysqli_error($stusystem));
$row_position = mysqli_fetch_assoc($position);

if (isset($_GET['totalRows_position'])) {
  $totalRows_position = $_GET['totalRows_position'];
} else {
  $all_position = mysqli_query($stusystem, $query_position);
  $totalRows_position = mysqli_num_rows($all_position);
}
$totalPages_position = ceil($totalRows_position/$maxRows_position)-1;$maxRows_position = 10;
$pageNum_position = 0;
if (isset($_GET['pageNum_position'])) {
  $pageNum_position = $_GET['pageNum_position'];
}
$startRow_position = $pageNum_position * $maxRows_position;

$colname_position = "''";
if (isset($_GET['word'])) {
  $colname_position = $_GET['word'];
}
//42320819($database_stusystem, $stusystem);
$query_position = sprintf("SELECT * FROM tbl_position WHERE Positionname LIKE %s", GetSQLValueString("%" . $colname_position . "%", "text"));
$query_limit_position = sprintf("%s LIMIT %d, %d", $query_position, $startRow_position, $maxRows_position);
$position = mysqli_query($stusystem, $query_limit_position) or die(mysqli_error($stusystem));
$row_position = mysqli_fetch_assoc($position);

if (isset($_GET['totalRows_position'])) {
  $totalRows_position = $_GET['totalRows_position'];
} else {
  $all_position = mysqli_query($stusystem, $query_position);
  $totalRows_position = mysqli_num_rows($all_position);
}
$totalPages_position = ceil($totalRows_position/$maxRows_position)-1;

$queryString_position = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_position") == false && 
        stristr($param, "totalRows_position") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_position = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_position = sprintf("&totalRows_position=%d%s", $totalRows_position, $queryString_position);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลตำแหน่ง</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php $page_active= 14 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการข้อมูลตำแหน่ง</h1>
       <div class="panel panel-primary">
      <div class="panel-heading"><h4>ค้นหาข้อมูล</h4></div>
      <div class="panel-body">
   <form action="search.php" method="get" id="search">

    <div class="col-sm-6"><input name="word" type="text" class="form-control" id="word" placeholder="ใส่ชื่อตำแหน่ง"></div>
    
  
 <div class="col-sm-12"><br></div>
  <div class="col-sm-1">
  <input name="search" type="submit" id="search" value="ค้นหา" class="btn btn-success">
  
  
  
  </div>
  </form>
    <br>
    <div style="text-align:right;">
  
  </div>
    </div>
    </div>
     
    <div class="col-sm-12"><br></div>
   <div class="col-sm-12">
     <?php if ($totalRows_position > 0) { // Show if recordset not empty ?>
      <div class="alert alert-success">
   จำนวน  <strong style="color:#000DFF;"><?php echo $totalRows_position ?></strong> ตำแหน่ง 
</div>

  <?php } // Show if recordset not empty ?>
   </div>
   <div class="col-sm-12"></div>
  <a href="insert.php" class="btn btn-success">เพิ่มข้อมูลตำแหน่ง</a><br>
  <?php if ($totalRows_position > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ตำแหน่ง</th>
        <th>แก้ไข</th>
        <th>ลบ</th>
        
        </tr>
      </thead>
    <tbody>
      <?php do { ?>
      <tr>
        
        <td><?php echo $row_position['Positionname']; ?>&nbsp;</td>
        
        <td><a href="update.php?PositionID=<?php echo $row_position['PositionID']; ?>" class="btn btn-warning">แก้ไขข้อมูล</a></td>
        <td><a href="delete.php?PositionID=<?php echo $row_position['PositionID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        </tr>
      <?php } while ($row_position = mysqli_fetch_assoc($position)); ?>
      </tbody>
  </table>
    
    
    <ul class="pagination">
      <li><a href="<?php printf("%s?pageNum_position=%d%s", $currentPage, 0, $queryString_position); ?>">หน้าแรก</a></li>
      <li><a href="<?php printf("%s?pageNum_position=%d%s", $currentPage, max(0, $pageNum_position - 1), $queryString_position); ?>">หน้าก่อนหน้า</a></li>
      <?php $pid = $_GET["pageNum_position"];   ?>
      <?php  
   for($dw_i=0;$dw_i<=$totalPages_position;$dw_i++){  ?>
      <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="?pageNum_position=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li>
      <?php
   }
   ?>
      <li><a href="<?php printf("%s?pageNum_position=%d%s", $currentPage, min($totalPages_position, $pageNum_position + 1), $queryString_position); ?>">หน้าต่อไป</a></li>
      <li><a href="<?php printf("%s?pageNum_position=%d%s", $currentPage, $totalPages_position, $queryString_position); ?>">หน้าสุดท้าย</a></li>
    </ul>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_position == 0) { // Show if recordset empty ?>
      ไม่พบข้อมูลอาจารย์
  <?php } // Show if recordset empty ?>
</div>
</body>
</html>
<?php
mysqli_free_result($position);
?>
