<?php require_once('../Connections/stusystem.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_group = 10;
$pageNum_group = 0;
if (isset($_GET['pageNum_group'])) {
  $pageNum_group = $_GET['pageNum_group'];
}
$startRow_group = $pageNum_group * $maxRows_group;

//42320819($database_stusystem, $stusystem);
$query_group = "SELECT a.*,b.*,c.*,d.* FROM tbl_group as a   left join tbl_classlevel as b on a.classlevelid=b.classlevelid  left join tbl_teacher as c on a.TeacherID=c.TeacherID left join tbl_prefix as d on c.PrefixCode=d.PrefixCode WHERE (a.classlevelid = b.classlevelid or b.classlevelid is null) and (a.TeacherID=c.TeacherID or c.TeacherID is null) and (c.PrefixCode=d.PrefixCode or d.PrefixCode is null)";
$query_limit_group = sprintf("%s LIMIT %d, %d", $query_group, $startRow_group, $maxRows_group);
$group = mysqli_query($stusystem, $query_limit_group) or die(mysqli_error($stusystem));
$row_group = mysqli_fetch_assoc($group);

if (isset($_GET['totalRows_group'])) {
  $totalRows_group = $_GET['totalRows_group'];
} else {
  $all_group = mysqli_query($stusystem, $query_group);
  $totalRows_group = mysqli_num_rows($all_group);
}
$totalPages_group = ceil($totalRows_group/$maxRows_group)-1;

//42320819($database_stusystem, $stusystem);
$query_classlevel = "SELECT * FROM tbl_classlevel";
$classlevel = mysqli_query($stusystem, $query_classlevel) or die(mysqli_error($stusystem));
$row_classlevel = mysqli_fetch_assoc($classlevel);
$totalRows_classlevel = mysqli_num_rows($classlevel);

$queryString_group = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_group") == false && 
        stristr($param, "totalRows_group") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_group = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_group = sprintf("&totalRows_group=%d%s", $totalRows_group, $queryString_group);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลนักเรียน</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php $page_active= 6 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการข้อมูลห้องเรียน</h1>
         <div class="panel panel-primary">
      <div class="panel-heading"><h4>ค้นหาข้อมูล</h4></div>
      <div class="panel-body">
   <form action="search.php" method="get" id="search">

    <div class="col-sm-3"><select name="classlevel" id="classlevel" class="form-control">
      <option value="" >คำนำหน้าชื่อ</option>
      <?php
do {  
?>
      <option value="<?php echo $row_classlevel['classlevelid']?>"><?php echo $row_classlevel['classlevelname']?></option>
      <?php
} while ($row_classlevel = mysqli_fetch_assoc($classlevel));
  $rows = mysqli_num_rows($classlevel);
  if($rows > 0) {
      mysqli_data_seek($classlevel, 0);
	  $row_classlevel = mysqli_fetch_assoc($classlevel);
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
  <a href="insert.php" class="btn btn-success">เพิ่มข้อมูลห้องเรียน</a>
  <br>
  <?php if ($totalRows_group > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>รหัสห้องเรียน</th>
        <th>ชื่อห้อง</th>
        <th>คุณครูที่รับผิดชอบ</th>
        <th>ระดับชั้น</th>
        <th>รายชื่อนักเรียน</th>
         <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <th>แก้ไข</th>
        <th>ลบ</th>
        <?php  }?>
        </tr>
      </thead>
    <tbody>
      <?php do { ?>
      <tr>
        <td><?php echo $row_group['groupid']; ?></td>
        <td><?php echo $row_group['group_name']; ?></td>
        <td><?php echo $row_group['PrefixName']; ?>  <?php echo $row_group['name']; ?> <?php echo $row_group['surname']; ?></td>
        <td><?php echo $row_group['classlevelname']; ?></td>
        <td><a href="grouplist.php?groupid=<?php echo $row_group['groupid']; ?>" class="btn btn-primary">รายชื่อนักเรียน</a></td>
          <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <td><a href="update.php?groupid=<?php echo $row_group['groupid']; ?>" class="btn btn-warning">แก้ไข</a></td>
        <td><a href="delete.php?groupid=<?php echo $row_group['groupid']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        
       <?Php } ?>
       
        </tr>
      <?php } while ($row_group = mysqli_fetch_assoc($group)); ?>
      </tbody>
  </table>
    
    <ul class="pagination">
      <li><a href="<?php printf("%s?pageNum_group=%d%s", $currentPage, 0, $queryString_group); ?>">หน้าแรก</a></li>
      <li><a href="<?php printf("%s?pageNum_group=%d%s", $currentPage, max(0, $pageNum_group - 1), $queryString_group); ?>">หน้าก่อนหน้า</a></li>
      <?php $pid = $_GET["pageNum_group"];   ?>
      <?php  
   for($dw_i=0;$dw_i<=$totalPages_group;$dw_i++){  ?>
      <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="?pageNum_group=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li>
      <?php
   }
   ?>
      <li><a href="<?php printf("%s?pageNum_group=%d%s", $currentPage, min($totalPages_group, $pageNum_group + 1), $queryString_group); ?>">หน้าต่อไป</a></li>
      <li><a href="<?php printf("%s?pageNum_group=%d%s", $currentPage, $totalPages_group, $queryString_group); ?>">หน้าสุดท้าย</a></li>
    </ul>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_group == 0) { // Show if recordset empty ?>
      ไม่พบข้อมูลนักเรียน
  <?php } // Show if recordset empty ?>
</div>
</body>
</html>
<?php
mysqli_free_result($group);

mysqli_free_result($classlevel);
?>
