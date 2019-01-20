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
$query_studentdata = sprintf("SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.*,i.*,j.* FROM tbl_student as a left join tbl_prefix as b on a.PrefixCode=b.PrefixCode left join tbl_gender as c on  a.GenderCode=c.GenderCode left join tbl_blger as d on  a.blgerid=d.blgerid  left join tbl_religion as e on  a.Religionid=e.Religionid left join tbl_group as f on a.groupid=f.groupid  left join tbl_studentstatus as g on  a.statusid=g.statusid left join tbl_classlevel as h on  a.GradeLevel=h.classlevelid left join tbl_academicyears as i on  a.AcademicYears=i.AcademicYearsID left join tbl_semester as j on  a.Semester=j.SemesterID WHERE a.studentID = %s and  (a.PrefixCode=b.PrefixCode or b.PrefixCode is null ) and  (a.GenderCode=c.GenderCode or c.GenderCode is null ) and  (a.blgerid=d.blgerid or d.blgerid is null ) and ( a.Religionid=e.Religionid or e.Religionid is null ) and  (a.groupid=f.groupid or f.groupid is null ) and ( a.statusid=g.statusid or g.statusid is null  ) and ( a.GradeLevel=h.classlevelid or h.classlevelid is null ) and ( a.AcademicYears=i.AcademicYearsID or i.AcademicYearsID is null )  and ( a.Semester=j.SemesterID or j.SemesterID is null  )", GetSQLValueString($colname_studentdata, "text"));
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

//42320819($database_stusystem, $stusystem);
$query_entry = "SELECT * FROM tbl_academicyears";
$entry = mysqli_query($stusystem, $query_entry) or die(mysqli_error($stusystem));
$row_entry = mysqli_fetch_assoc($entry);
$totalRows_entry = mysqli_num_rows($entry);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการข้อมูลนักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 1;
?>

  <?php include ("../../head.php");?>
  <!---เมนูหน้าข้อมูลนักเรียน--->
  <?php include ("../student-menu.php");?>
  <!--จบเมนูหน้าข้อมูลนักเรียน-->
  
  <div class="col-sm-7">
    <h1 style="text-align:center;">ข้อมูลนักเรียน</h1>
    <h3 style="text-align:center;"><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?>  <?php echo $row_studentdata['student_surname']; ?></h3> <div style="text-align:right">
    <a href="update.php?studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-warning">แก้ไขข้อมูลนักเรียน</a>
    <a href="delete.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
    </div>
    <br>
    <table class="table table-hover">
      
      <tbody>
        <tr>
          <td>รหัสนักเรียน</td>
          <td><?php echo $row_studentdata['studentID']; ?></td>
        </tr>
        <tr>
          <td>รหัสประชาชน</td>
          <td><?php echo $row_studentdata['PersonID']; ?></td>
        </tr>
       
        <tr>
          <td>ชื่อ - นามสกุล</td>
          <td><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?> <?php echo $row_studentdata['student_surname']; ?></td>
        </tr>
        
        <tr>
          <td>เพศ</td>
          <td><?php echo $row_studentdata['GenderName']; ?></td>
        </tr>
        <tr>
          <td>หมู่เลือด</td>
          <td><?php echo $row_studentdata['blgerName']; ?></td>
        </tr>
        <tr>
          <td>วันเดือนปีเกิด</td>
         
          
          <td>
          <?php
		
		echo $row_studentdata['student_Birthdate'];
		
		
		 ?>
          </td>
        </tr>
        <tr>
          <td>ชื่อเล่น</td>
          <td><?php echo $row_studentdata['Student_Nickname']; ?></td>
        </tr>
        <tr>
          <td>ศาสนา</td>
          <td><?php echo $row_studentdata['Religionname']; ?></td>
        </tr>
        
        <tr>
        <td>ปีการศึกษาที่เข้าเรียน</td>
        <td>
		<?php do { ?>
		<?php
        
		if($row_entry['AcademicYearsID']==$row_studentdata['EntryAcademicYear']){
		 echo $row_entry['AcademicYears']; 
		}
		 
		 ?>
		<?php } while ($row_entry = mysqli_fetch_assoc($entry)); ?>
        
        </td>
        
        </tr>
        <tr>
        
        <td>ปีการศึกษา</td>
        <td><?php echo $row_studentdata['AcademicYears']; ?></td>
        </tr>
        
        <?php if($row_studentdata['statusid']!=2){ ?>
        
        <tr>
          <td>ระดับชั้น</td>
          <td><?php echo $row_studentdata['classlevelname']; ?></td>
        </tr>
        <tr>
          <td>ห้องเรียน</td>
          <td><?php echo $row_studentdata['group_name']; ?></td>
        </tr>
        <tr>
        
        <tr>
        <td>ภาคเรียน</td>
        <td><?php echo $row_studentdata['Semester']; ?></td>
        </tr>
        
        <?php }?>
        
        <tr>
          <td>สถาณะนักเรียน</td>
          <Td><?php echo $row_studentdata['Statusname']; ?></Td>
        </tr>
        <tr>
          <td>เบอร์โทรศัพท์นักเรียน</td>
          <Td><?php echo $row_studentdata['stu_tel']; ?></Td>
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

mysqli_free_result($entry);
?>
