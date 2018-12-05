<?php require_once('../../Connections/stusystem.php'); ?>
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
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("dw-upload.inc.php");?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "insertstudentdata")) {
	
	
	if($_POST['student_Birthdate']!=''){
		$date = $_POST['student_Birthdate'];
		$show=explode("-",$date);
		$date1 = $show[0];
		$date2 = $show[1];
		$date3 = $show[2]-543;
		$dateval =  $date3."/".$date2."/".$date1;
		
		}else{
			$dateval= "0000/00/00";
			}
	
	
  $updateSQL = sprintf("UPDATE tbl_student SET PrefixCode=%s, student_name=%s, student_surname=%s, GenderCode=%s, blgerid=%s, student_Birthdate=%s, Student_Nickname=%s, Religionid=%s, GradeLevel=%s, groupid=%s, EntryAcademicYear=%s, AcademicYears=%s, Semester=%s, statusid=%s, stu_tel=%s WHERE studentID=%s AND PersonID=%s",
                       GetSQLValueString($_POST['PrefixCode'], "int"),
                       GetSQLValueString($_POST['student_name'], "text"),
                       GetSQLValueString($_POST['student_surname'], "text"),
                       GetSQLValueString($_POST['GenderCode'], "int"),
                       GetSQLValueString($_POST['blgerid'], "int"),
                       GetSQLValueString($dateval, "date"),
                       GetSQLValueString($_POST['student_Nickname'], "text"),
                       GetSQLValueString($_POST['Religionid'], "int"),
                       GetSQLValueString($_POST['classlevelid'], "int"),
                       GetSQLValueString($_POST['groupid'], "text"),
                       GetSQLValueString($_POST['EntryAcademicYear'], "int"),
                       GetSQLValueString($_POST['AcademicYears'], "int"),
                       GetSQLValueString($_POST['Semester'], "int"),
                       GetSQLValueString($_POST['statusid'], "int"),
                       GetSQLValueString($_POST['stu_tel'], "text"),
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['PersonID'], "text"));

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}







//42320819($database_stusystem, $stusystem);
$query_Prefix = "SELECT * FROM tbl_prefix";
$Prefix = mysqli_query($stusystem, $query_Prefix) or die(mysqli_error($stusystem));
$row_Prefix = mysqli_fetch_assoc($Prefix);
$totalRows_Prefix = mysqli_num_rows($Prefix);

//42320819($database_stusystem, $stusystem);
$query_Gender = "SELECT * FROM tbl_gender";
$Gender = mysqli_query($stusystem, $query_Gender) or die(mysqli_error($stusystem));
$row_Gender = mysqli_fetch_assoc($Gender);
$totalRows_Gender = mysqli_num_rows($Gender);

//42320819($database_stusystem, $stusystem);
$query_blger = "SELECT * FROM tbl_blger";
$blger = mysqli_query($stusystem, $query_blger) or die(mysqli_error($stusystem));
$row_blger = mysqli_fetch_assoc($blger);
$totalRows_blger = mysqli_num_rows($blger);

//42320819($database_stusystem, $stusystem);
$query_Riligion = "SELECT * FROM tbl_religion";
$Riligion = mysqli_query($stusystem, $query_Riligion) or die(mysqli_error($stusystem));
$row_Riligion = mysqli_fetch_assoc($Riligion);
$totalRows_Riligion = mysqli_num_rows($Riligion);

//42320819($database_stusystem, $stusystem);
$query_studentstatus = "SELECT * FROM tbl_studentstatus";
$studentstatus = mysqli_query($stusystem, $query_studentstatus) or die(mysqli_error($stusystem));
$row_studentstatus = mysqli_fetch_assoc($studentstatus);
$totalRows_studentstatus = mysqli_num_rows($studentstatus);

//42320819($database_stusystem, $stusystem);
$query_academicyear = "SELECT * FROM tbl_academicyears";
$academicyear = mysqli_query($stusystem, $query_academicyear) or die(mysqli_error($stusystem));
$row_academicyear = mysqli_fetch_assoc($academicyear);
$totalRows_academicyear = mysqli_num_rows($academicyear);

//42320819($database_stusystem, $stusystem);
$query_semester = "SELECT * FROM tbl_semester";
$semester = mysqli_query($stusystem, $query_semester) or die(mysqli_error($stusystem));
$row_semester = mysqli_fetch_assoc($semester);
$totalRows_semester = mysqli_num_rows($semester);

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
$query_Gradelevel = "SELECT * FROM tbl_classlevel";
$Gradelevel = mysqli_query($stusystem, $query_Gradelevel) or die(mysqli_error($stusystem));
$row_Gradelevel = mysqli_fetch_assoc($Gradelevel);
$totalRows_Gradelevel = mysqli_num_rows($Gradelevel);

//42320819($database_stusystem, $stusystem);
$query_group = "SELECT * FROM tbl_group";
$group = mysqli_query($stusystem, $query_group) or die(mysqli_error($stusystem));
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);

//42320819($database_stusystem, $stusystem);
$query_EntryAcademicYear = "SELECT * FROM tbl_academicyears";
$EntryAcademicYear = mysqli_query($stusystem, $query_EntryAcademicYear) or die(mysqli_error($stusystem));
$row_EntryAcademicYear = mysqli_fetch_assoc($EntryAcademicYear);
$totalRows_EntryAcademicYear = mysqli_num_rows($EntryAcademicYear);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ปรับปรุงข้อมูลนักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script src="../../js/jquery-1.11.2.min.js"></script>
<script src="../../js/bootstrap.js"></script>
<script src="../../js/jquery-1.11.2.min.js"></script>
 <link rel="stylesheet" href="../../css/jquery.datetimepicker.css">

</head>

<body action="<?php echo $editFormAction; ?>">

<?php include ("../../head.php");?>

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="insertstudentdata" id="insertstudentdata">
<div class="container">
 <a href="index.php?studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-primary">กลับไปหน้าข้อมูลนักเรียน</a> <h2 style="text-align:center;">ปรับปรุงข้อมูลนักเรียน</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสนักเรียน</td>
        <td><input name="StudentID" type="text" autofocus required="required" class="form-control" id="StudentID" placeholder="ใส่รหัสนักเรียน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_student['studentID']; ?>" maxlength="8" readonly></td>
      </tr>
      <tr>
        <td>รหัสบัตรประจำตัวประชาชน</td>
        <td><input name="PersonID" type="text" required="required" class="form-control" id="PersonID" placeholder="ใส่รหัสบัตรประชาชน เฉพาะตัวเลขเท่านั้น"pattern="[0-9]{1,}" value="<?php echo $row_student['PersonID']; ?>" size="13" maxlength="13" readonly></td>
      </tr>
      <tr>
        <td>คำนำหน้าชื่อ</td>
        <td><select name="PrefixCode" id="PrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Prefix['PrefixCode']?>"<?php if (!(strcmp($row_Prefix['PrefixCode'], $row_student['PrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Prefix['PrefixName']?></option>
          <?php
} while ($row_Prefix = mysqli_fetch_assoc($Prefix));
  $rows = mysqli_num_rows($Prefix);
  if($rows > 0) {
      mysqli_data_seek($Prefix, 0);
	  $row_Prefix = mysqli_fetch_assoc($Prefix);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td>ชื่อ</td>
        <td><input name="student_name" type="text" required="required" class="form-control" id="student_name" placeholder="ชื่อ กรอกเฉพาะตัวอักษรเท่านั้น" value="<?php echo $row_student['student_name']; ?>" >&nbsp;</td>
      </tr>
      <tr>
        <td>นามสกุล</td>
        <td><input name="student_surname" type="text" required="required" class="form-control" id="student_surname" placeholder="นามสกุล  กรอกเฉพาะตัวอักษรเท่านั้น" value="<?php echo $row_student['student_surname']; ?>" ></td>
      </tr>
      
      <tr>
      <tr>
        <td>เพศ</td>
        <td><select name="GenderCode" id="GenderCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Gender['GenderCode']?>"<?php if (!(strcmp($row_Gender['GenderCode'], $row_student['GenderCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Gender['GenderName']?></option>
          <?php
} while ($row_Gender = mysqli_fetch_assoc($Gender));
  $rows = mysqli_num_rows($Gender);
  if($rows > 0) {
      mysqli_data_seek($Gender, 0);
	  $row_Gender = mysqli_fetch_assoc($Gender);
  }
?>
        </select>
          &nbsp;</td>
      </tr>
      <tr>
        <td>หมู่เลือด</td>
        <td><select name="blgerid" id="blgerid">
          <?php
do {  
?>
          <option value="<?php echo $row_blger['blgerid']?>"<?php if (!(strcmp($row_blger['blgerid'], $row_student['blgerid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_blger['blgerName']?></option>
          <?php
} while ($row_blger = mysqli_fetch_assoc($blger));
  $rows = mysqli_num_rows($blger);
  if($rows > 0) {
      mysqli_data_seek($blger, 0);
	  $row_blger = mysqli_fetch_assoc($blger);
  }
?>
        </select>
          &nbsp;</td>
      </tr>
      <tr>
        <td>วัน/เดือน/ปี เกิด</td>
  
        <td> <input type="text" name="student_Birthdate" id="student_Birthdate" value="<?php
		if($row_student['student_Birthdate']!=''){
		$date = $row_student['student_Birthdate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."-".$date2."-".$date1;
		echo $dateval;
		}
		
		 ?>"  class="form-control">&nbsp;</td>
        <!---Datepicker สคริป-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  
<script src="../../js/jquery.datetimepicker.full.js"></script>
<script type="text/javascript">   
$(function(){
     
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
     // กรณีใช้แบบ input
    $("#student_Birthdate").datetimepicker({
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
    $("#student_Birthdate").on("mouseenter mouseleave",function(e){
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
        <td>ชื่อเล่น</td>
        <td><input name="student_Nickname" type="text" required="required" class="form-control" id="student_Nickname" placeholder="ชื่อเล่น" value="<?php echo $row_student['Student_Nickname']; ?>"></td>
      </tr>
      <tr>
        <td>ศาสนา</td>
        <td><select name="Religionid" id="Religionid">
          <?php
do {  
?>
          <option value="<?php echo $row_Riligion['Religionid']?>"<?php if (!(strcmp($row_Riligion['Religionid'], $row_student['Religionid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Riligion['Religionname']?></option>
          <?php
} while ($row_Riligion = mysqli_fetch_assoc($Riligion));
  $rows = mysqli_num_rows($Riligion);
  if($rows > 0) {
      mysqli_data_seek($Riligion, 0);
	  $row_Riligion = mysqli_fetch_assoc($Riligion);
  }
?>
        </select>
          &nbsp;</td>
      </tr>
      <tr>
          <td>ระดับชั้น</td>
          <td><!-- แสดงตัวเลือก จังหวัด -->
            <select name="classlevelid" required  id="classlevelid">
              <option value="1" <?php if (!(strcmp(1, $row_student['GradeLevel']))) {echo "selected=\"selected\"";} ?>>-- กรุณาเลือกระดับชั้น --</option>
              <?php
do {  
?>
              <option value="<?php echo $row_Gradelevel['classlevelid']?>"<?php if (!(strcmp($row_Gradelevel['classlevelid'], $row_student['GradeLevel']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Gradelevel['classlevelname']?></option>
              <?php
} while ($row_Gradelevel = mysqli_fetch_assoc($Gradelevel));
  $rows = mysqli_num_rows($Gradelevel);
  if($rows > 0) {
      mysqli_data_seek($Gradelevel, 0);
	  $row_Gradelevel = mysqli_fetch_assoc($Gradelevel);
  }
?>
            </select></td>
        </tr>
        <tr>
          <td>ห้อง</td>
          <td><!-- แสดงตัวเลือก ตำบล -->
            <select name="groupid" required  id="groupid">
              <option value="1" <?php if (!(strcmp(1, $row_student['groupid']))) {echo "selected=\"selected\"";} ?>>-- กรุณาเลือกห้องเรียน --</option>
              <?php
do {  
?>
              <option value="<?php echo $row_group['groupid']?>"<?php if (!(strcmp($row_group['groupid'], $row_student['groupid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_group['group_name']?></option>
              <?php
} while ($row_group = mysqli_fetch_assoc($group));
  $rows = mysqli_num_rows($group);
  if($rows > 0) {
      mysqli_data_seek($group, 0);
	  $row_group = mysqli_fetch_assoc($group);
  }
?>
            </select></td>
            </tr>
		      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
          <script>
			
			$(function(){
				
				//เรียกใช้งาน Select2
				$(".select2-single").select2();
				
			
				
				
				//แสดงข้อมูล อำเภอ และ ตำบล  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#classlevelid").change(function(){

					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var province_id = $(this).val();
					
					$.ajax({
						url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{province_id:province_id},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
						success:function(data){
							
							//กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
							$("#groupid").text("");
							
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data  
							$.each(data, function( index, value ) {
								
								//แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
								  $("#groupid").append("<option value='"+ value.id +"'> " + value.name + "</option>");
							});
						}
					});
					
					

				});
				
				
				
				
			});
			
	</script>	
      
     
     <tr>
      <td>ปีการศึกษาที่เข้าเรียน</td>
      <td><select name="EntryAcademicYear" id="EntryAcademicYear">
        <option value="" <?php if (!(strcmp("", $row_student['EntryAcademicYear']))) {echo "selected=\"selected\"";} ?>>ปีการศึกษาที่เข้าเรียน</option>
        <?php
do {  
?>
        <option value="<?php echo $row_EntryAcademicYear['AcademicYearsID']?>"<?php if (!(strcmp($row_EntryAcademicYear['AcademicYearsID'], $row_student['EntryAcademicYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_EntryAcademicYear['AcademicYears']?></option>
        <?php
} while ($row_EntryAcademicYear = mysqli_fetch_assoc($EntryAcademicYear));
  $rows = mysqli_num_rows($EntryAcademicYear);
  if($rows > 0) {
      mysqli_data_seek($EntryAcademicYear, 0);
	  $row_EntryAcademicYear = mysqli_fetch_assoc($EntryAcademicYear);
  }
?>
      </select></td>
      
      </tr>
     
     
      <tr>
      <td>ปีการศึกษา</td>
      <td><select name="AcademicYears" id="AcademicYears">
        <?php
do {  
?>
        <option value="<?php echo $row_academicyear['AcademicYearsID']?>"<?php if (!(strcmp($row_academicyear['AcademicYearsID'], $row_student['AcademicYears']))) {echo "selected=\"selected\"";} ?>><?php echo $row_academicyear['AcademicYears']?></option>
        <?php
} while ($row_academicyear = mysqli_fetch_assoc($academicyear));
  $rows = mysqli_num_rows($academicyear);
  if($rows > 0) {
      mysqli_data_seek($academicyear, 0);
	  $row_academicyear = mysqli_fetch_assoc($academicyear);
  }
?>
      </select></td>
      
      </tr>
       <tr>
      <td>ภาคเรียน</td>
      <td><select name="Semester" id="Semester">
        <?php
do {  
?>
        <option value="<?php echo $row_semester['SemesterID']?>"<?php if (!(strcmp($row_semester['SemesterID'], $row_student['Semester']))) {echo "selected=\"selected\"";} ?>><?php echo $row_semester['Semester']?></option>
        <?php
} while ($row_semester = mysqli_fetch_assoc($semester));
  $rows = mysqli_num_rows($semester);
  if($rows > 0) {
      mysqli_data_seek($semester, 0);
	  $row_semester = mysqli_fetch_assoc($semester);
  }
?>
      </select></td>
      
      </tr>
      <tr>
        <td>สถาณะการเรียน</td>
        <td><select name="statusid" id="statusid">
          <?php
do {  
?>
          <option value="<?php echo $row_studentstatus['statusid']?>"<?php if (!(strcmp($row_studentstatus['statusid'], $row_student['statusid']))) {echo "selected=\"selected\"";} ?>><?php echo $row_studentstatus['Statusname']?></option>
          <?php
} while ($row_studentstatus = mysqli_fetch_assoc($studentstatus));
  $rows = mysqli_num_rows($studentstatus);
  if($rows > 0) {
      mysqli_data_seek($studentstatus, 0);
	  $row_studentstatus = mysqli_fetch_assoc($studentstatus);
  }
?>
        </select>
          &nbsp;</td>
      </tr>
      <tr>
        <td>โทรศัพท์</td>
        <td><input name="stu_tel" type="text" required class="form-control" id="stu_tel" placeholder="เบอร์โทรศัพท์" value="<?php echo $row_student['stu_tel']; ?>">&nbsp;</td>
      </tr>
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลนักเรียน" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertstudentdata">
<input type="hidden" name="MM_update" value="insertstudentdata">
</form>



</body>

</html>
<?php
mysqli_free_result($Prefix);

mysqli_free_result($Gender);

mysqli_free_result($blger);

mysqli_free_result($Riligion);

mysqli_free_result($studentstatus);

mysqli_free_result($academicyear);

mysqli_free_result($semester);

mysqli_free_result($student);

mysqli_free_result($Gradelevel);

mysqli_free_result($group);

mysqli_free_result($EntryAcademicYear);
?>
