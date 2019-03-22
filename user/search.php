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

$maxRows_user = 10;
$pageNum_user = 0;
if (isset($_GET['pageNum_user'])) {
  $pageNum_user = $_GET['pageNum_user'];
}
$startRow_user = $pageNum_user * $maxRows_user;

$colname_user = "''";
if (isset($_GET['word'])) {
  $colname_user = $_GET['word'];
}
$colname2_user = "''";
if (isset($_GET['word'])) {
  $colname2_user = $_GET['word'];
}

if(!empty($_GET['usertypeid']) && $_GET['usertypeid']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.usertypeid='.$_GET['usertypeid'];
	
	}

	if(!empty($arrWhere)){
	$strSQLWhere = implode(' and ', $arrWhere);
	if(count($arrWhere)>0){
		$strSQLWhere=' and '.$strSQLWhere;
		}
	
	}


//42320819($database_stusystem, $stusystem);
$query_user = sprintf("SELECT a.*,b.* FROM tbl_user as a left join tbl_usertype as b on  a.usertypeid=b.usertypeid WHERE (a.username like %s or a.user_name like %s) and(a.usertypeid=b.usertypeid or b.usertypeid is null  ) ".$strSQLWhere." ", GetSQLValueString("%" . $colname_user . "%", "text"),GetSQLValueString("%" . $colname2_user . "%", "text"));
$user = mysqli_query($stusystem, $query_user) or die(mysqli_error($stusystem));
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);

//42320819($database_stusystem, $stusystem);
$query_usertype = "SELECT * FROM tbl_usertype";
$usertype = mysqli_query($stusystem, $query_usertype) or die(mysqli_error($stusystem));
$row_usertype = mysqli_fetch_assoc($usertype);
$totalRows_usertype = mysqli_num_rows($usertype);

$queryString_user = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_user") == false && 
        stristr($param, "totalRows_user") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_user = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_user = sprintf("&totalRows_user=%d%s", $totalRows_user, $queryString_user);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลสมาชิก</title>

</head>

<body>
<?php $page_active= 5 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการข้อมูลสมาชิก</h1>
        <div class="panel panel-primary">
      <div class="panel-heading"><h4>ค้นหาข้อมูล</h4></div>
      <div class="panel-body">
   <form action="search.php" method="get" id="search">

    <div class="col-sm-6">
    <input name="word" type="text" class="form-control" id="word" placeholder="กรอก Username หรือ ฃื่อผู้ใช้ระบบ" value="<?php echo $_GET['word'] ?>">
    </div>
    <div class="col-sm-3"><select name="usertypeid" id="usertypeid" class="form-control">
      <option value="" <?php if (!(strcmp("", $row_user['usertypeid']))) {echo "selected=\"selected\"";} ?>>เลือกระดับสมาชิก</option>
      <?php
do {  
?>
      <option value="<?php echo $row_usertype['usertypeid']?>"<?php if (!(strcmp($row_usertype['usertypeid'], $row_user['usertypeid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_usertype['usertype_name']?></option>
      <?php
} while ($row_usertype = mysqli_fetch_assoc($usertype));
  $rows = mysqli_num_rows($usertype);
  if($rows > 0) {
      mysqli_data_seek($usertype, 0);
	  $row_usertype = mysqli_fetch_assoc($usertype);
  }
?>
    </select></div>
  
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
     <?php if ($totalRows_user > 0) { // Show if recordset not empty ?>
      <div class="alert alert-success">
   จำนวน  <strong style="color:#000DFF;"><?php echo $totalRows_user ?></strong> คน 
</div>

  <?php } // Show if recordset not empty ?>
   </div>
   <div class="col-sm-12"></div>
    
  <a href="insert.php" class="btn btn-success">เพิ่มข้อมูลสมาชิก</a>
  <br>
  <?php if ($totalRows_user > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ชื่อสมาชิก</th>
        <th>Username</th>
        <th>ระดับสมาชิก</th>
        <th>แก้ไข</th>
        <th>ลบ</th>
        </tr>
      </thead>
    <tbody>
      <?php do { ?>
      <tr>
        <td><?php echo $row_user['user_name']; ?>&nbsp;</td>
        <td><?php echo $row_user['username']; ?>&nbsp;</td>
        <td><?php echo $row_user['usertype_name']; ?>&nbsp;</td>
        <td><a href="update.php?userid=<?php echo $row_user['userid']; ?>" class="btn btn-warning">แก้ไขข้อมูล</a></td>
        <td><a href="delete.php?userid=<?php echo $row_user['userid']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        
        </tr>
      <?php } while ($row_user = mysqli_fetch_assoc($user)); ?>
      </tbody>
  </table>
    
    <ul class="pagination">
      <li><a href="<?php printf("%s?pageNum_user=%d%s", $currentPage, 0, $queryString_user); ?>">หน้าแรก</a></li>
      <li><a href="<?php printf("%s?pageNum_user=%d%s", $currentPage, max(0, $pageNum_user - 1), $queryString_user); ?>">หน้าก่อนหน้า</a></li>
      <?php $pid = $_GET["pageNum_user"];   ?>
      <?php  
   for($dw_i=0;$dw_i<=$totalPages_user;$dw_i++){  ?>
      <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="../student/?pageNum_user=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li>
      <?php
   }
   ?>
      <li><a href="<?php printf("%s?pageNum_user=%d%s", $currentPage, min($totalPages_user, $pageNum_user + 1), $queryString_user); ?>">หน้าต่อไป</a></li>
      <li><a href="<?php printf("%s?pageNum_user=%d%s", $currentPage, $totalPages_user, $queryString_user); ?>">หน้าสุดท้าย</a></li>
    </ul>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_user == 0) { // Show if recordset empty ?>
      ไม่พบข้อมูลนักเรียน
  <?php } // Show if recordset empty ?>
</div>
</body>
</html>
<?php
mysqli_free_result($user);

mysqli_free_result($usertype);
?>
