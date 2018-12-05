<?php require_once('../Connections/stusystem.php'); ?>
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

//42320819($database_stusystem, $stusystem);
$query_prefix = "SELECT * FROM tbl_prefix";
$prefix = mysqli_query($stusystem, $query_prefix) or die(mysqli_error($stusystem));
$row_prefix = mysqli_fetch_assoc($prefix);
$totalRows_prefix = mysqli_num_rows($prefix);

//42320819($database_stusystem, $stusystem);
$query_Gender = "SELECT * FROM tbl_gender";
$Gender = mysqli_query($stusystem, $query_Gender) or die(mysqli_error($stusystem));
$row_Gender = mysqli_fetch_assoc($Gender);
$totalRows_Gender = mysqli_num_rows($Gender);

$colname_Teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_Teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_Teacher = sprintf("SELECT * FROM tbl_teacher WHERE TeacherID = %s", GetSQLValueString($colname_Teacher, "text"));
$Teacher = mysqli_query($stusystem, $query_Teacher) or die(mysqli_error($stusystem));
$row_Teacher = mysqli_fetch_assoc($Teacher);
$totalRows_Teacher = mysqli_num_rows($Teacher);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>เพิ่มข้อมูลที่อยู่นักเรียน</title>
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../dist/js/bootstrap.min.js"></script>
<script
  src="https://code.jquery.com/jquery-1.10.2.js"
  integrity="sha256-it5nQKHTz+34HijZJQkpNBIHsjpV8b6QzMJs9tmOBSo="
  crossorigin="anonymous"></script>

<!-- นำเข้า Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
	<!-- นำเข้า Select2 CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
 
</head>

<body action="<?php echo $editFormAction; ?>">

<?php include ("../head.php");?>

<form method="POST" enctype="multipart/form-data" name="insertteacherdata" id="insertteacherdata">
<div class="container">
<a href="data.php?TeacherID=<?php echo $row_Teacher['TeacherID']; ?>" class="btn btn-primary">กลับไปหน้าข้อมูลอาจารย์</a>
 <h2 style="text-align:center;">ปรับปรุงข้อมูลอาจารย์</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสอาจารย์</td>
        <td><input name="TeacherID" type="text" required="required" class="form-control" id="TeacherID" placeholder="ใส่รหัสอาจารย์ เฉพาะตัวเลขเท่านั้น"pattern="[0-9]{1,}" value="<?php echo $row_Teacher['TeacherID']; ?>" readonly ></td>
      </tr>
      <tr>
        <td>รหัสประชาชน</td>
        <td><input name="PersonID" type="text" required="required" class="form-control" id="PersonID" placeholder="ใส่รหัสประชาชน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_Teacher['PersonID']; ?>" ></td>
      </tr>
       
       <tr>
        <td>คำนำหน้าชื่อ</td>
        <td><select name="PrefixCode" id="PrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_prefix['PrefixCode']?>"<?php if (!(strcmp($row_prefix['PrefixCode'], $row_Teacher['PrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prefix['PrefixName']?></option>
          <?php
} while ($row_prefix = mysqli_fetch_assoc($prefix));
  $rows = mysqli_num_rows($prefix);
  if($rows > 0) {
      mysqli_data_seek($prefix, 0);
	  $row_prefix = mysqli_fetch_assoc($prefix);
  }
?>
        </select></td>
      </tr>
       <tr>
        <td>ชื่อ</td>
        <td><input name="Teacher_name" type="text" required="required" class="form-control" id="Teacher_name" placeholder="ใส่ชื่ออาจารย์" value="<?php echo $row_Teacher['Teacher_name']; ?>" ></td>
      </tr>
       <tr>
        <td>นามสกุล</td>
        <td><input name="Teacher_surname" type="text" required="required" class="form-control" id="Teacher_surname" placeholder="ใส่นามสกุลอาจารย์" value="<?php echo $row_Teacher['Teacher_surname']; ?>" ></td>
      </tr>
       
       <tr>
        <td>เพศ</td>
        <td><select name="GenderCode" id="GenderCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Gender['GenderCode']?>"<?php if (!(strcmp($row_Gender['GenderCode'], $row_Teacher['GenderCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Gender['GenderName']?></option>
          <?php
} while ($row_Gender = mysqli_fetch_assoc($Gender));
  $rows = mysqli_num_rows($Gender);
  if($rows > 0) {
      mysqli_data_seek($Gender, 0);
	  $row_Gender = mysqli_fetch_assoc($Gender);
  }
?>
        </select></td>
      </tr>
       <tr>
        <td>เบอร์โทรศัพท์</td>
        <td><input name="Teacher_tel" type="text"  class="form-control" id="Teacher_tel" placeholder="ใส่เบอร์โทรศัพท์" value="<?php echo $row_Teacher['Teacher_tel']; ?>" ></td>
      </tr>
       <tr>
        <td>E-mail </td>
        <td><input name="Teacher_email" type="email"  class="form-control" id="Teacher_email" placeholder="ใส่ E-mail" value="<?php echo $row_Teacher['Teacher_email']; ?>" ></td>
      </tr>
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลอาจารย์" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
</form>


</body>

</html>
<?php
mysqli_free_result($prefix);

mysqli_free_result($Gender);


