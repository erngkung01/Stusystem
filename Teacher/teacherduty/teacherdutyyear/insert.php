<?php require_once('../../../Connections/stusystem.php'); ?>
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("../../teacheraward/dw-upload.inc.php");?>
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

$MM_restrictGoTo = "../../../index.php";
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

$colname_teacherID = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacherID = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacherID = sprintf("SELECT * FROM tbl_teacher WHERE TeacherID = %s", GetSQLValueString($colname_teacherID, "text"));
$teacherID = mysqli_query($stusystem, $query_teacherID) or die(mysqli_error($stusystem));
$row_teacherID = mysqli_fetch_assoc($teacherID);
$totalRows_teacherID = mysqli_num_rows($teacherID);

//42320819($database_stusystem, $stusystem);
$query_academicyear = "SELECT * FROM tbl_academicyears";
$academicyear = mysqli_query($stusystem, $query_academicyear) or die(mysqli_error($stusystem));
$row_academicyear = mysqli_fetch_assoc($academicyear);
$totalRows_academicyear = mysqli_num_rows($academicyear);

$colname_teacheraward = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacheraward = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacheraward = sprintf("SELECT * FROM tbl_teacher WHERE TeacherID = %s", GetSQLValueString($colname_teacheraward, "text"));
$teacheraward = mysqli_query($stusystem, $query_teacheraward) or die(mysqli_error($stusystem));
$row_teacheraward = mysqli_fetch_assoc($teacheraward);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "insertstudentparent")) {
  $insertSQL = sprintf("INSERT INTO tbl_dutyyear (AcademicYearsID, TeacherID) VALUES (%s, %s)",
                       GetSQLValueString($_POST['AcademicYearsID'], "int"),
                       GetSQLValueString($_POST['TeacherID'], "text"));

 //dwthai.com($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $insertSQL) or die(mysqli_error($stusystem));

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>เพิ่มข้อมูลปีการศึกษา</title>
<link href="../../../css/bootstrap.css" rel="stylesheet" type="text/css">
<script src="../../../js/jquery-1.11.2.min.js"></script>
<script src="../../../dist/js/bootstrap.min.js"></script>
<script
  src="https://code.jquery.com/jquery-1.10.2.js"
  integrity="sha256-it5nQKHTz+34HijZJQkpNBIHsjpV8b6QzMJs9tmOBSo="
  crossorigin="anonymous"></script>
<link rel="stylesheet" href="../../../js/jquery.datetimepicker.css">
<!-- นำเข้า Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
	<!-- นำเข้า Select2 CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
 
</head>

<body action="<?php echo $editFormAction; ?>">

<?php include ("../../../head.php");?>

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="insertstudentparent" id="insertstudentparent">
<div class="container">
<a href="../../teacheraward/index.php?TeacherID=<?php echo $row_teacheraward['TeacherID']; ?>" class="btn btn-primary">กลับไปหน้า</a>
 <h2 style="text-align:center;">เพิ่มข้อมูลปีการศึกษา</h2>
     
  <table height="238" class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสอาจารย์</td>
        <td><input name="TeacherID" type="text" required="required" class="form-control" id="TeacherID" placeholder="ใส่รหัสนักเรียน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_teacherID['TeacherID']; ?>" readonly></td>
      </tr>
       <tr>
        <td>ชื่อ</td>
        <td><input name="Teacher_name" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่ชื่อ" value="<?php echo $row_teacherID['name']; ?>" readonly></td>
      </tr>
       <tr>
        <td>นามสกุล</td>
        <td><input name="Teacher_surname" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่นามสกุล" value="<?php echo $row_teacherID['surname']; ?>"  readonly></td>
      </tr>
      <tr>
      <td>ปีการศึกษา</td>
      <td><select name="AcademicYearsID" id="AcademicYearsID">
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
   
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentaward" type="submit" id="inserstudentaward" value="เพิ่มข้อมูลปีการศึกษา" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertstudentparent">
</form>


</body>

</html>
<?php
mysqli_free_result($teacherID);

mysqli_free_result($academicyear);
?>
