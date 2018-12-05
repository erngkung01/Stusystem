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

$colname_award = "-1";
if (isset($_GET['AwardID'])) {
  $colname_award = $_GET['AwardID'];
}
//42320819($database_stusystem, $stusystem);
$query_award = sprintf("SELECT a.*,b.*,c.*,d.* FROM tbl_teacheraward as a, tbl_academicyears as b, tbl_semester as c, tbl_awardtype as d WHERE a.AwardID = %s and a.AwardYear=b.AcademicYearsID and a.AwardSemester=c.SemesterID and a.AwardTypeID=d.AwardTypeID", GetSQLValueString($colname_award, "int"));
$award = mysqli_query($stusystem, $query_award) or die(mysqli_error($stusystem));
$row_award = mysqli_fetch_assoc($award);
$totalRows_award = mysqli_num_rows($award);

$colname_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacher = sprintf("SELECT a.*,b.* FROM tbl_teacher as a, tbl_prefix as b WHERE a.TeacherID = %s and a.PrefixCode=b.PrefixCode", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ข้อมูลผลงานรางวัล</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 10;
?>

  <?php include ("../../head.php");?>
  <!---เมนูหน้าข้อมูลนักเรียน--->
<?php include ("../teacher-menu.php");?>
  <!--จบเมนูหน้าข้อมูลนักเรียน-->
  
  <div class="col-sm-7">
    <h1 style="text-align:center;">ข้อมูลผลงานรางวัล</h1>
    <h3 style="text-align:center;"><?php echo $row_teacher['PrefixName']; ?><?php echo $row_teacher['name']; ?> <?php echo $row_teacher['surname']; ?></h3> <div style="text-align:right">
   <a href="update.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>&AwardID=<?php echo $row_award['AwardID']; ?>" class="btn btn-warning">แก้ไขข้อมูลรางวัล</a>
  <a href="delete.php?AwardID=<?php echo $row_award['AwardID']; ?>&TeacherID=<?php echo $row_teacher['TeacherID']; ?>&TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
    </div>
    <br>
    <table class="table table-hover">
      
      <tbody>
        <tr>
          <td>รหัสอาจารย์</td>
          <td><?php echo $row_teacher['TeacherID']; ?>&nbsp;</td>
        </tr>
         <tr>
          <td>ชื่อ</td>
          <td><?php echo $row_teacher['name']; ?>&nbsp;</td>
        </tr>
         <tr>
          <td>นามสกุล</td>
          <td><?php echo $row_teacher['surname']; ?>&nbsp;</td>
        </tr>
         <tr>
          <td>ชื่อผลงาน/รางวัล</td>
          <td><?php echo $row_award['AwardName']; ?>&nbsp;</td>
        </tr>
        <tr>
          <td>ปีการศึกษา</td>
          <td><?php echo $row_award['AcademicYears']; ?>&nbsp;</td>
        </tr>
        <tr>
        <td>ภาคเรียน</td>
        <td><?php echo $row_award['Semester']; ?>&nbsp;</td>
        </tr>
        <tr>
          <td>รูปภาพ</td>
          <td><img src="images/<?php echo $row_award['AwardPic']; ?>" id="" alt="" width="150" height="150"></td>
        </tr>
        
        <tr>
          <td>วันที่ได้รับใบประกาศ</td>
         
          
          <td>
          <?php
		if($row_award['AwardDate']!=''){
		$date = $row_award['AwardDate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."/".$date2."/".$date1;
		echo $dateval;
		}
		
		 ?>
          </td>
        </tr>
        <tr>
        <td>รายละเอียดผลงาน/รางวัล</td>
        <td><?php echo $row_award['AwardDetail']; ?>&nbsp;</td>
        </tr>
        <tr>
        <td>ผู้มอบใบประกาศ</td>
        <td><?php echo $row_award['AwardBy']; ?>&nbsp;</td>
        </tr>
       <tr>
       <td>ประเภทผลงาน/รางวัล</td>
       <td><?php echo $row_award['AwardTypeName']; ?>&nbsp;</td>
       </tr>
      </tbody>
    </table>
  </div>
  
  <div class="col-sm-2">
    <div style="text-align:center;">
      <img src="../images/<?php echo $row_teacher['Teacher_img']; ?>" id="" alt="" width="150" height="150">
    </div>
    <br>
    <div style="text-align:center;">
      <a href="../Uploading.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-primary">แก้ไขรูปภาพ</a>
    </div>
  </div>
  
  
  
</body>
</html>
<?php
mysqli_free_result($award);

mysqli_free_result($teacher);
?>
