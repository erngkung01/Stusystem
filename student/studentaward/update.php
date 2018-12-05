<?php require_once('../../Connections/stusystem.php'); ?>
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("dw-upload.inc.php");?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update")) {
	if($_POST['AwardDate']!=""){
		$date = $_POST['AwardDate'];
		$show=explode("-",$date);
		$date1 = $show[0];
		$date2 = $show[1];
		$date3 = $show[2]-543;
		$dateval =  $date3."/".$date2."/".$date1;
		
		}else{
			
			}
	
	if($_FILES['fileField']['name']!=""){
  $updateSQL = sprintf("UPDATE tbl_award SET AwardYear=%s, AwardSemester=%s, AwardName=%s, AwardDetail=%s, AwardPic=%s, AwardDate=%s, AwardBy=%s, studentID=%s, AwardTypeID=%s WHERE AwardID=%s",
                       GetSQLValueString($_POST['AwardYear'], "int"),
                       GetSQLValueString($_POST['AwardSemester'], "int"),
                       GetSQLValueString($_POST['AwardName'], "text"),
                       GetSQLValueString($_POST['AwardDetail'], "text"),
                       GetSQLValueString(dwUpload($_FILES['fileField']), "text"),
                       GetSQLValueString($dateval, "date"),
                       GetSQLValueString($_POST['AwardBy'], "text"),
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['AwardTypeID'], "int"),
                       GetSQLValueString($_POST['AwardID'], "int"));
					   
	}else{
		
		$updateSQL = sprintf("UPDATE tbl_award SET AwardYear=%s, AwardSemester=%s, AwardName=%s, AwardDetail=%s,  AwardDate=%s, AwardBy=%s, studentID=%s, AwardTypeID=%s WHERE AwardID=%s",
                       GetSQLValueString($_POST['AwardYear'], "int"),
                       GetSQLValueString($_POST['AwardSemester'], "int"),
                       GetSQLValueString($_POST['AwardName'], "text"),
                       GetSQLValueString($_POST['AwardDetail'], "text"),
                       GetSQLValueString($dateval, "date"),
                       GetSQLValueString($_POST['AwardBy'], "text"),
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['AwardTypeID'], "int"),
                       GetSQLValueString($_POST['AwardID'], "int"));
		
		}

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "view.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT studentID, student_name, student_surname FROM tbl_student WHERE studentID = %s", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

//42320819($database_stusystem, $stusystem);
$query_academicYear = "SELECT * FROM tbl_academicyears ORDER BY AcademicYears DESC";
$academicYear = mysqli_query($stusystem, $query_academicYear) or die(mysqli_error($stusystem));
$row_academicYear = mysqli_fetch_assoc($academicYear);
$totalRows_academicYear = mysqli_num_rows($academicYear);

//42320819($database_stusystem, $stusystem);
$query_Semester = "SELECT * FROM tbl_semester";
$Semester = mysqli_query($stusystem, $query_Semester) or die(mysqli_error($stusystem));
$row_Semester = mysqli_fetch_assoc($Semester);
$totalRows_Semester = mysqli_num_rows($Semester);

//42320819($database_stusystem, $stusystem);
$query_Awardtype = "SELECT * FROM tbl_awardtype";
$Awardtype = mysqli_query($stusystem, $query_Awardtype) or die(mysqli_error($stusystem));
$row_Awardtype = mysqli_fetch_assoc($Awardtype);
$totalRows_Awardtype = mysqli_num_rows($Awardtype);

$colname_studentaward = "-1";
if (isset($_GET['AwardID'])) {
  $colname_studentaward = $_GET['AwardID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaward = sprintf("SELECT * FROM tbl_award WHERE AwardID = %s", GetSQLValueString($colname_studentaward, "int"));
$studentaward = mysqli_query($stusystem, $query_studentaward) or die(mysqli_error($stusystem));
$row_studentaward = mysqli_fetch_assoc($studentaward);
$totalRows_studentaward = mysqli_num_rows($studentaward);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>เพิ่มข้อมูลผลงาน/รางวัลนักเรียน</title>
<link href="../../css/bootstrap.css" rel="stylesheet" type="text/css">
<script src="../../js/jquery-1.11.2.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<script
  src="https://code.jquery.com/jquery-1.10.2.js"
  integrity="sha256-it5nQKHTz+34HijZJQkpNBIHsjpV8b6QzMJs9tmOBSo="
  crossorigin="anonymous"></script>
<link rel="stylesheet" href="../../js/jquery.datetimepicker.css">
<!-- นำเข้า Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
	<!-- นำเข้า Select2 CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
 
</head>

<body action="<?php echo $editFormAction; ?>">

<?php include ("../../head.php");?>

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="update" id="update">
<div class="container">
<a href="view.php?studentID=<?php echo $row_studentdata['studentID']; ?>&AwardID=<?php echo $row_studentaward['AwardID']; ?>" class="btn btn-primary">กลับไปหน้าข้อมูลผลงาน/รางวัลนักเรียน</a>
 <h2 style="text-align:center;">ปรับปรุงข้อมูลผลงาน/รางวัลนักเรียน</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสนักเรียน</td>
        <td><input name="StudentID" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่รหัสนักเรียน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_studentdata['studentID']; ?>" readonly></td>
      </tr>
       <tr>
        <td>ชื่อ</td>
        <td><input name="student_name" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่ชื่อ" value="<?php echo $row_studentdata['student_name']; ?>" readonly></td>
      </tr>
       <tr>
        <td>นามสกุล</td>
        <td><input name="student_surname" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่นามสกุล" value="<?php echo $row_studentdata['student_surname']; ?>"  readonly></td>
      </tr>
      <tr>
      <td>ชื่อผลงานรางวัล</td>
      <td><input name="AwardName" type="text" class="form-control" id="AwardName" value="<?php echo $row_studentaward['AwardName']; ?>"></td>
      </tr>
      <tr>
      <td>ปีการศึกษา</td>
      <td><select name="AwardYear" id="AwardYear">
        <?php
do {  
?>
        <option value="<?php echo $row_academicYear['AcademicYearsID']?>"<?php if (!(strcmp($row_academicYear['AcademicYearsID'], $row_studentaward['AwardYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_academicYear['AcademicYears']?></option>
        <?php
} while ($row_academicYear = mysqli_fetch_assoc($academicYear));
  $rows = mysqli_num_rows($academicYear);
  if($rows > 0) {
      mysqli_data_seek($academicYear, 0);
	  $row_academicYear = mysqli_fetch_assoc($academicYear);
  }
?>
      </select></td>
      </tr>
      <tr>
      <td>ภาคเรียน</td>
      <td><select name="AwardSemester" id="AwardSemester">
        <?php
do {  
?>
        <option value="<?php echo $row_Semester['SemesterID']?>"<?php if (!(strcmp($row_Semester['SemesterID'], $row_studentaward['AwardSemester']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Semester['Semester']?></option>
        <?php
} while ($row_Semester = mysqli_fetch_assoc($Semester));
  $rows = mysqli_num_rows($Semester);
  if($rows > 0) {
      mysqli_data_seek($Semester, 0);
	  $row_Semester = mysqli_fetch_assoc($Semester);
  }
?>
      </select></td>
      </tr>
      <tr>
      <td>รายละเอียดผลงาน/รางวัล</td>
      <td>
      	<script src="ckeditor.js"></script>

		<!-- Sample 2 -->
		<textarea cols="80" id="AwardDetail" name="AwardDetail" class="ckedtor" rows="10">
		<?php echo $row_studentaward['AwardDetail']; ?>
        
        </textarea>
		<script>

			CKEDITOR.replace( 'AwardDetail');

		</script>
      
      </td>
      </tr>
        <tr>
      <td>รูปภาพประกอบ</td>
      <td>
        <input type="file" name="fileField" id="fileField">
        <img src="images/<?php echo $row_studentaward['AwardPic']; ?>" id="img" alt="" height="250" width="250"></td>
      </tr>
       <tr>
      <td>วันที่ได้รับ </td>
       <td> <input type="text" name="AwardDate" id="AwardDate" value="<?php
		if($row_studentaward['AwardDate']!=""){
		$date = $row_studentaward['AwardDate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."-".$date2."-".$date1;
		echo $dateval;
		}
		
		 ?>"  class="form-control">&nbsp;</td>
        <!---Datepicker สคริป-->
       <script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>
  
<script type="text/javascript" src="../../js/jquery.datetimepicker.full.js"></script>

<script type="text/javascript">   
$(function(){
     
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
     // กรณีใช้แบบ input
    $("#AwardDate").datetimepicker({
        timepicker:false,
        format:'d-m-Y',  // กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000            
        lang:'th',  // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
        onSelectDate:function(dp,$input){
            var yearT=new Date(dp).getFullYear()-0;  
            var yearTH=yearT+543;
            var fulldate=$input.val();
            var fulldateTH=fulldate.replace(yearT,yearTH);
            $input.val(fulldateTH);
        },
    });       
    // กรณีใช้กับ input ต้องกำหนดส่วนนี้ด้วยเสมอ เพื่อปรับปีให้เป็น ค.ศ. ก่อนแสดงปฏิทิน
    $("#AwardDate").on("mouseenter mouseleave",function(e){
        var dateValue=$(this).val();
        if(dateValue!=""){
                var arr_date=dateValue.split("-"); // ถ้าใช้ตัวแบ่งรูปแบบอื่น ให้เปลี่ยนเป็นตามรูปแบบนั้น
                // ในที่นี้อยู่ในรูปแบบ 00-00-0000 เป็น d-m-Y  แบ่งด่วย - ดังนั้น ตัวแปรที่เป็นปี จะอยู่ใน array
                //  ตัวที่สอง arr_date[2] โดยเริ่มนับจาก 0 
                if(e.type=="mouseenter"){
                    var yearT=arr_date[2]-543;
                }       
                if(e.type=="mouseleave"){
                    var yearT=parseInt(arr_date[2])+543;
                }   
                dateValue=dateValue.replace(arr_date[2],yearT);
                $(this).val(dateValue);                                                 
        }       
    });
     
     
});
</script>
        <!---จบ datepicker สคริป-->
      </tr>
        <tr>
      <td>ผู้มอบใบประกาศผลงาน/รางวัล</td>
      <td><input name="AwardBy" type="text" class="form-control" id="AwardBy" value="<?php echo $row_studentaward['AwardBy']; ?>"></td>
      </tr>
       <tr>
      <td>ประเภทผลงาน/รางวัล</td>
      <td><select name="AwardTypeID" id="AwardTypeID">
        <?php
do {  
?>
        <option value="<?php echo $row_Awardtype['AwardTypeID']?>"<?php if (!(strcmp($row_Awardtype['AwardTypeID'], $row_studentaward['AwardTypeID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Awardtype['AwardTypeName']?></option>
        <?php
} while ($row_Awardtype = mysqli_fetch_assoc($Awardtype));
  $rows = mysqli_num_rows($Awardtype);
  if($rows > 0) {
      mysqli_data_seek($Awardtype, 0);
	  $row_Awardtype = mysqli_fetch_assoc($Awardtype);
  }
?>
      </select></td>
      </tr>
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentaward" type="submit" id="inserstudentaward" value="ปรับปรุงข้อมูลผลงาน/รางวัลนักเรียน" class="btn btn-success" ><input name="AwardID" type="hidden" id="AwardID" value="<?php echo $row_studentaward['AwardID']; ?>"></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertstudentparent">
<input type="hidden" name="MM_update" value="update">
</form>


</body>

</html>
<?php
mysqli_free_result($studentdata);

mysqli_free_result($academicYear);

mysqli_free_result($Semester);

mysqli_free_result($Awardtype);

mysqli_free_result($studentaward);
?>
