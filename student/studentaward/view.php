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

$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.* FROM tbl_student as a, tbl_prefix as b,tbl_gender as c,tbl_blger as d,tbl_religion as e,tbl_group as f,tbl_studentstatus as g,tbl_classlevel as h WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode and a.GenderCode=c.GenderCode and a.blgerid=d.blgerid and a.Religionid=e.Religionid and a.groupid=f.groupid and a.statusid=g.statusid and a.GradeLevel=h.classlevelid", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

$colname_student = "-1";
if (isset($_GET['studentID'])) {
  $colname_student = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_student = sprintf("SELECT * FROM tbl_student WHERE studentID = %s", GetSQLValueString($colname_student, "text"));
$student = mysqli_query($stusystem, $query_student) or die(mysqli_error($stusystem));
$row_student = mysqli_fetch_assoc($student);
$totalRows_student = mysqli_num_rows($student);

$colname_studentaward = "-1";
if (isset($_GET['AwardID'])) {
  $colname_studentaward = $_GET['AwardID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaward = sprintf("SELECT a.*,b.*,c.*,d.* FROM tbl_award as a, tbl_academicyears as b, tbl_semester as c,tbl_awardtype as d WHERE a.AwardID = %s and a.AwardYear=b.AcademicYearsID and  a.AwardSemester=c.SemesterID and a.AwardTypeID=d.AwardTypeID", GetSQLValueString($colname_studentaward, "int"));
$studentaward = mysqli_query($stusystem, $query_studentaward) or die(mysqli_error($stusystem));
$row_studentaward = mysqli_fetch_assoc($studentaward);
$totalRows_studentaward = mysqli_num_rows($studentaward);
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
$page_active= 8;
?>

  <?php include ("../../head.php");?>
  <!---เมนูหน้าข้อมูลนักเรียน--->
  <?php include ("../student-menu.php");?>
  <!--จบเมนูหน้าข้อมูลนักเรียน-->
  
  <div class="col-sm-7">
    <h1 style="text-align:center;">ข้อมูลผลงานรางวัล</h1>
    <h3 style="text-align:center;"><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?>  <?php echo $row_studentdata['student_surname']; ?></h3> <div style="text-align:right">
    <a href="update.php?AwardID=<?php echo $row_studentaward['AwardID']; ?>&studentID=<?php echo $row_studentaward['studentID']; ?>"  class="btn btn-warning">แก้ไขข้อมูลรางวัล</a>
   <a href="delete.php?AwardID=<?php echo $row_studentaward['AwardID']; ?>&studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
    </div>
    <br>
    <table class="table table-hover">
      
      <tbody>
        <tr>
          <td>รหัสนักเรียน</td>
          <td><?php echo $row_studentdata['studentID']; ?></td>
        </tr>
         <tr>
          <td>ชื่อ</td>
          <td><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?> </td>
        </tr>
         <tr>
          <td>นามสกุล</td>
          <td><?php echo $row_studentdata['student_surname']; ?></td>
        </tr>
         <tr>
          <td>ชื่อผลงาน/รางวัล</td>
          <td><?php echo $row_studentaward['AwardName']; ?></td>
        </tr>
        <tr>
          <td>ปีการศึกษา</td>
          <td><?php echo $row_studentaward['AcademicYears']; ?></td>
        </tr>
        <tr>
        <td>ภาคเรียน</td>
        <td><?php echo $row_studentaward['Semester']; ?></td>
        </tr>
        <tr>
          <td>รูปภาพ</td>
          <td><img src="images/<?php echo $row_studentaward['AwardPic']; ?>" id="" alt="" height="150" width="150"></td>
        </tr>
        
        <tr>
          <td>วันที่ได้รับใบประกาศ</td>
         
          
          <td>
          <?php
		if($row_studentaward['AwardDate']!=''){
		$date = $row_studentaward['AwardDate'];
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
        <td><?php echo $row_studentaward['AwardDetail']; ?></td>
        </tr>
        <tr>
        <td>ผู้มอบใบประกาศ</td>
        <td><?php echo $row_studentaward['AwardBy']; ?></td>
        </tr>
       <tr>
       <td>ประเภทผลงาน/รางวัล</td>
       <td><?php echo $row_studentaward['AwardTypeName']; ?></td>
       </tr>
      </tbody>
    </table>
  </div>
  
  <div class="col-sm-2">
   
   <?php include ("../student-menu2.php");?>
   
  </div>
  
  
  
</body>
</html>
<?php
mysqli_free_result($studentdata);

mysqli_free_result($student);

mysqli_free_result($studentaward);
?>
