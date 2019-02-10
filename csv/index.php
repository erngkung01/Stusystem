<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "111";
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
<?php require_once('../Connections/stusystem.php'); ?>
<?php
//index.php
$connect = mysqli_connect($hostname_stusystem, $username_stusystem, $password_stusystem, $database_stusystem);
mysqli_set_charset($connect, "utf8");
$message = '';

if(isset($_POST["upload"]))
{
 if($_FILES['product_file']['name'])
 {
  $filename = explode(".", $_FILES['product_file']['name']);
  if(end($filename) == "csv")
  {
   $handle = fopen($_FILES['product_file']['tmp_name'], "r");
   while($data = fgetcsv($handle))
   {
    $studentID = mysqli_real_escape_string($connect, $data[0]);
	$PersonID = mysqli_real_escape_string($connect, $data[1]);
	$PrefixCode = mysqli_real_escape_string($connect, $data[2]);
	$student_name = mysqli_real_escape_string($connect, $data[3]);
	$student_surname = mysqli_real_escape_string($connect, $data[4]);
	$GenderCode = mysqli_real_escape_string($connect, $data[5]);
	$blgerid = mysqli_real_escape_string($connect, $data[6]);
	$student_Birthdate = mysqli_real_escape_string($connect, $data[7]);
	$Student_Nickname = mysqli_real_escape_string($connect, $data[8]);
	$Religionid = mysqli_real_escape_string($connect, $data[9]);
	$GradeLevel = mysqli_real_escape_string($connect, $data[10]);
	$groupid = mysqli_real_escape_string($connect, $data[11]);
	$EntryAcademicYear = mysqli_real_escape_string($connect, $data[12]);
	$AcademicYears = mysqli_real_escape_string($connect, $data[13]);
	$Semester = mysqli_real_escape_string($connect, $data[14]);
	$statusid = mysqli_real_escape_string($connect, $data[15]);
	$stu_tel = mysqli_real_escape_string($connect, $data[16]);
	$Homeid = mysqli_real_escape_string($connect, $data[17]);
	$HouseNo = mysqli_real_escape_string($connect, $data[18]);
	$moo = mysqli_real_escape_string($connect, $data[19]);
	$street = mysqli_real_escape_string($connect, $data[20]);
	$DISTRICT_ID = mysqli_real_escape_string($connect, $data[21]);
	$AMPHUR_ID = mysqli_real_escape_string($connect, $data[22]);
	$PROVINCE_ID = mysqli_real_escape_string($connect, $data[23]);
	$ZipCode = mysqli_real_escape_string($connect, $data[24]);
	$stu_gps = mysqli_real_escape_string($connect, $data[25]);
	$FatherPrefixCode = mysqli_real_escape_string($connect, $data[26]);
	$FatherName = mysqli_real_escape_string($connect, $data[27]);
	$FatherSurName = mysqli_real_escape_string($connect, $data[28]);
	$FatherPersonID =  mysqli_real_escape_string($connect, $data[29]);
	$Father_tel = mysqli_real_escape_string($connect, $data[30]);
	$Fatheroccupa = mysqli_real_escape_string($connect, $data[31]);
	$FatherSalary = mysqli_real_escape_string($connect, $data[32]);
	$MatherPrefixCode = mysqli_real_escape_string($connect, $data[33]);
	$Mathername = mysqli_real_escape_string($connect, $data[34]);
	$Mathersurname = mysqli_real_escape_string($connect, $data[35]);
	$MatherPersonID = mysqli_real_escape_string($connect, $data[36]);
	$Mather_tel = mysqli_real_escape_string($connect, $data[37]);
	$Matheroccupa = mysqli_real_escape_string($connect, $data[38]);
	$MatherSalary = mysqli_real_escape_string($connect, $data[39]);
	$ParentPrefixCode = mysqli_real_escape_string($connect, $data[40]);
	$ParentName = mysqli_real_escape_string($connect, $data[41]);
	$ParentSurname = mysqli_real_escape_string($connect, $data[42]);
	$ParentPersonID = mysqli_real_escape_string($connect, $data[43]);
	$Parent_tel = mysqli_real_escape_string($connect, $data[44]);
	$Parentoccupa = mysqli_real_escape_string($connect, $data[45]);
	$ParentSalary = mysqli_real_escape_string($connect, $data[46]);
	
    
   
   
   
		
		$query = "
    INSERT INTO tbl_student (studentID, PersonID, PrefixCode, student_name, student_surname, GenderCode, blgerid, student_Birthdate, Student_Nickname, Religionid, GradeLevel, groupid, EntryAcademicYear, Semester, statusid, stu_tel )
VALUES ('$studentID', '$PersonID', '$PrefixCode', '$student_name', '$student_surname', '$GenderCode', '$blgerid', '$student_Birthdate', '$Student_Nickname', '$Religionid', '$GradeLevel', '$groupid', '$EntryAcademicYear', '$Semester', '$statusid', '$stu_tel')
    ";
    mysqli_query($connect, $query);
	
	$query2 = "
    INSERT INTO tbl_address (studentID, PersonID, Homeid, HouseNo, moo, street, DISTRICT_ID, AMPHUR_ID, PROVINCE_ID, ZipCode, stu_gps)
VALUES ('$studentID', '$PersonID', '$Homeid', '$HouseNo', '$moo', '$street', '$DISTRICT_ID', '$AMPHUR_ID', '$PROVINCE_ID', '$ZipCode', '$stu_gps' )
    ";
    mysqli_query($connect, $query2);
	
	
	$query3 = "
    INSERT INTO tbl_parent (studentID, PersonID, FatherPrefixCode, FatherName, FatherSurName, FatherPersonID, Father_tel, Fatheroccupa, FatherSalary, MatherPrefixCode, Mathername, Mathersurname, MatherPersonID, Mather_tel, Matheroccupa, MatherSalary, ParentPrefixCode, ParentName, ParentSurname, 	ParentPersonID, Parent_tel, Parentoccupa, ParentSalary )
VALUES ('$studentID', '$PersonID', '$FatherPrefixCode', '$FatherName', '$FatherSurName', '$FatherPersonID', '$Father_tel', '$Fatheroccupa', '$FatherSalary', '$MatherPrefixCode', '$Mathername', '$Mathersurname', '$MatherPersonID', '$Mather_tel', '$Matheroccupa', '$MatherSalary', '$ParentPrefixCode', '$ParentName', '$ParentSurname', '$ParentPersonID', '$Parent_tel', '$Parentoccupa', '$ParentSalary'  )
    ";
    mysqli_query($connect, $query3);
		
		
	
	
	
   }
  
   fclose($handle);
   header("location: index.php?updation=1");
  }
  else
  {
   $message = '<label class="text-danger">Please Select CSV File only</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">Please Select File</label>';
 }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">ดำเนินการเสร็จสมบูรณ์</label>';
}

$query = "SELECT * FROM daily_product";
$result = mysqli_query($connect, $query);
?>
<!DOCTYPE html>
<html>
 <head>
  <title>อัปโหลดไฟล์ CSV</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
 <?php include ("../head.php");?>
  <br />
  <div class="container">
   <h2 align="center">ระบบเพิ่ม/ปรับปรุง ข้อมูลนักเรียน อัตโนมัติผ่านไฟล์ CSV</a></h2>
   <br />
   <form method="post" enctype='multipart/form-data'>
    <p><label>โปรดเลือกไฟล์ CSV(ไฟล์ CSV เท่านั้น)</label>
    <input type="file" name="product_file" /></p>
    <br />
    <input type="submit" name="upload" class="btn btn-info" value="Upload" />
   </form>
   <br />
   
   <?php echo $message; ?>
   
  </div>
 </body>
</html>
