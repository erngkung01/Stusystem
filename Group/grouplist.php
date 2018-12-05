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

$colname_student = "-1";
if (isset($_GET['groupid'])) {
  $colname_student = $_GET['groupid'];
}
//42320819($database_stusystem, $stusystem);
$query_student = sprintf("SELECT a.*,b.* FROM tbl_student as a , tbl_prefix as b WHERE a.groupid = %s and a.statusid='1' and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_student, "text"));
$student = mysqli_query($stusystem, $query_student) or die(mysqli_error($stusystem));
$row_student = mysqli_fetch_assoc($student);
$totalRows_student = mysqli_num_rows($student);

$colname_grouplist = "-1";
if (isset($_GET['groupid'])) {
  $colname_grouplist = $_GET['groupid'];
}
//42320819($database_stusystem, $stusystem);
$query_grouplist = sprintf("SELECT * FROM tbl_group WHERE groupid = %s", GetSQLValueString($colname_grouplist, "text"));
$grouplist = mysqli_query($stusystem, $query_grouplist) or die(mysqli_error($stusystem));
$row_grouplist = mysqli_fetch_assoc($grouplist);
$totalRows_grouplist = mysqli_num_rows($grouplist);

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
<title>รายชื่อนักเรียน</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  
  
  <style type="text/css">
@media print{
	#btnprint{
		display:none;
		}
		#hide1{
			display:none;
			}
	@page 
    {
        size: auto;   /* กำหนดขนาดของหน้าเอกสารเป็นออโต้ครับ */
        margin: 0mm;  /* กำหนดขอบกระดาษเป็น 0 มม. */
    }

    body 
    {
        margin: 0px;  /* เป็นการกำหนดขอบกระดาษของเนื้อหาที่จะพิมพ์ก่อนที่จะส่งไปให้เครื่องพิมพ์ครับ */
    }
	}
</style>
  
  
</head>

<body>
<?php $page_active= 6 ;?>
<?php include("../head.php"); ?>
<?php if ($totalRows_student > 0) { // Show if recordset not empty ?>
  <div class="container">
    <h1 style="text-align:center;">รายชื่อนักเรียน ระดับชั้น <?php echo $row_grouplist['group_name']; ?></h1>
    
    <h4 style="text-align: center; color: #0516D4;">จำนวน <?php echo $totalRows_student ?> คน ดังนี้</h4>
    <h4 style="text-align: center; color: #0516D4;"> อาจารย์ที่รับผิดชอบ <?php echo $row_group['PrefixName']; ?><?php echo $row_group['name']; ?> <?php echo $row_group['surname']; ?></h4>
    <table style="text-align:center;" class="table table-hover">
      <tbody>
        <tr class="danger">
          <td width="15">ที่</td>
          <td width="119">รหัสนักเรียน</td>
          <td width="214">ชื่อ-นามสกุล</td>
          <td id="hide1" width="84">รายละเอียด</td>
         
        </tr>
        <?php $i=1; ?>
        <?php do { ?>
        <tr>
          <td><?php  echo "  "; echo $i; ?>&nbsp;</td>
          <td><?php echo $row_student['studentID']; ?></td>
          <td><?php echo $row_student['PrefixName']; ?><?php echo $row_student['student_name']; ?>  <?php echo $row_student['student_surname']; ?>  </td>
          <td id="hide1"><a href="../student/Studentdata/index.php?studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-primary">ดูข้อมูล</a></td>
          
        </tr>
        
        <?php $i++; ?>
        <?php } while ($row_student = mysqli_fetch_assoc($student)); ?>
      </tbody>
    </table>
    
    <br>
    <br>
    <div style="text-align:center;"><input name="btnprint" id="btnprint" type="button" onClick="window.print()" value="พิมพ์..." class="btn btn-primary"></div>
    <p>&nbsp;</p>
    
    
  </div>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysqli_free_result($group);

mysqli_free_result($classlevel);

mysqli_free_result($student);

mysqli_free_result($grouplist);
?>
