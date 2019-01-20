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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "insertstudentdata")) {
	
	
	
	
	
  $insertSQL = sprintf("INSERT INTO tbl_student (studentID, PersonID, PrefixCode, student_name, student_surname, student_img, GenderCode, blgerid, student_Birthdate, Student_Nickname, Religionid, GradeLevel, groupid, EntryAcademicYear, AcademicYears, Semester, statusid, stu_tel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['PersonID'], "text"),
                       GetSQLValueString($_POST['PrefixCode'], "int"),
                       GetSQLValueString($_POST['student_name'], "text"),
                       GetSQLValueString($_POST['student_surname'], "text"),
                       GetSQLValueString(dwUpload($_FILES['fileField']), "text"),
                       GetSQLValueString($_POST['GenderCode'], "int"),
                       GetSQLValueString($_POST['blgerid'], "int"),
                       GetSQLValueString($_POST['student_Birthdate'], "date"),
                       GetSQLValueString($_POST['student_Nickname'], "text"),
                       GetSQLValueString($_POST['Religionid'], "int"),
                       GetSQLValueString($_POST['classlevelid'], "int"),
                       GetSQLValueString($_POST['groupid'], "text"),
                       GetSQLValueString($_POST['EntryAcademicYear'], "int"),
                       GetSQLValueString($_POST['AcademicYears'], "int"),
                       GetSQLValueString($_POST['Semester'], "int"),
                       GetSQLValueString($_POST['statusid'], "int"),
                       GetSQLValueString($_POST['stu_tel'], "text"));

 //dwthai.com($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $insertSQL) or die(mysqli_error($stusystem));

  $insertGoTo = "../Studentaddress/insert.php?studentID=".$_POST['StudentID']."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
<title>เพิ่มข้อมูลนักเรียน</title>
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
 <a href="../index.php" class="btn btn-primary">กลับไปหน้าข้อมูลนักเรียน</a> <h2 style="text-align:center;">เพิ่มข้อมูลนักเรียน</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสนักเรียน</td>
        <td><input name="StudentID" type="text" autofocus required="required" class="form-control" id="StudentID" placeholder="ใส่รหัสนักเรียน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" maxlength="8"></td>
      </tr>
      <tr>
        <td>รหัสบัตรประจำตัวประชาชน</td>
        <td><input name="PersonID" type="text" required="required" class="form-control" id="PersonID" placeholder="ใส่รหัสบัตรประชาชน เฉพาะตัวเลขเท่านั้น"pattern="[0-9]{1,}" size="13" maxlength="13"></td>
      </tr>
      <tr>
        <td>คำนำหน้าชื่อ</td>
        <td><select name="PrefixCode" id="PrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Prefix['PrefixCode']?>"><?php echo $row_Prefix['PrefixName']?></option>
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
        <td><input name="student_name" type="text" required="required" class="form-control" id="student_name" placeholder="ชื่อ กรอกเฉพาะตัวอักษรเท่านั้น" >&nbsp;</td>
      </tr>
      <tr>
        <td>นามสกุล</td>
        <td><input name="student_surname" type="text" required="required" class="form-control" id="student_surname" placeholder="นามสกุล  กรอกเฉพาะตัวอักษรเท่านั้น" ></td>
      </tr>
      <tr>
        <td>รูปนักเรียน</td>
        <td><input type="file" name="fileField" id="fileField" class="form-control-file" required></td>
      </tr>
      <tr>
      <tr>
        <td>เพศ</td>
        <td><select name="GenderCode" id="GenderCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Gender['GenderCode']?>"><?php echo $row_Gender['GenderName']?></option>
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
          <option value="<?php echo $row_blger['blgerid']?>"><?php echo $row_blger['blgerName']?></option>
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
  
        <td> <input type="text" name="student_Birthdate" id="student_Birthdate" value=""  class="form-control">&nbsp;</td>
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
        <td><input name="student_Nickname" type="text" required="required" class="form-control" id="student_Nickname" placeholder="ชื่อเล่น"></td>
      </tr>
      <tr>
        <td>ศาสนา</td>
        <td><select name="Religionid" id="Religionid">
          <?php
do {  
?>
          <option value="<?php echo $row_Riligion['Religionid']?>"><?php echo $row_Riligion['Religionname']?></option>
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
              <option value="1">-- กรุณาเลือกระดับชั้น --</option>
          </select></td>
        </tr>
        <tr>
          <td>ห้อง</td>
          <td><!-- แสดงตัวเลือก ตำบล -->
            <select name="groupid" required  id="groupid">
              <option value="1">-- กรุณาเลือกห้องเรียน --</option>
          </select></td>
            </tr>
		      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
          <script>
			
			$(function(){
				
				//เรียกใช้งาน Select2
				$(".select2-single").select2();
				
				//ดึงข้อมูล province จากไฟล์ get_data.php
				$.ajax({
					url:"get_data.php",
					dataType: "json", //กำหนดให้มีรูปแบบเป็น Json
					data:{show_province:'show_province'}, //ส่งค่าตัวแปร show_province เพื่อดึงข้อมูล จังหวัด
					success:function(data){
						
						//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
						$.each(data, function( index, value ) {
							//แทรก Elements ใน id province  ด้วยคำสั่ง append
							  $("#classlevelid").append("<option value='"+ value.id +"'> " + value.name + "</option>");
						});
					}
				});
				
				
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
        <option value="">ปีการศึกษาที่เข้าเรียน</option>
        <?php
do {  
?>
        <option value="<?php echo $row_EntryAcademicYear['AcademicYearsID']?>"><?php echo $row_EntryAcademicYear['AcademicYears']?></option>
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
        <option value="<?php echo $row_academicyear['AcademicYearsID']?>"><?php echo $row_academicyear['AcademicYears']?></option>
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
        <option value="<?php echo $row_semester['SemesterID']?>"><?php echo $row_semester['Semester']?></option>
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
          <option value="<?php echo $row_studentstatus['statusid']?>"><?php echo $row_studentstatus['Statusname']?></option>
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
        <td><input name="stu_tel" type="text" class="form-control" id="stu_tel" placeholder="เบอร์โทรศัพท์" required>&nbsp;</td>
      </tr>
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="เพิ่มข้อมูลนักเรียน" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertstudentdata">
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

mysqli_free_result($EntryAcademicYear);
?>
