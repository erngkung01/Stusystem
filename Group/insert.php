<?php require_once('../Connections/stusystem.php'); ?>
<?php define("dwtuploadfolder","images");
 //example dwUpload($_FILES["input_name"]);
include("../Teacher/dw-upload.inc.php");?>
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



//42320819($database_stusystem, $stusystem);
$query_class = "SELECT * FROM tbl_classlevel";
$class = mysqli_query($stusystem, $query_class) or die(mysqli_error($stusystem));
$row_class = mysqli_fetch_assoc($class);
$totalRows_class = mysqli_num_rows($class);

//42320819($database_stusystem, $stusystem);
$query_teacher = "SELECT a.*,b.* FROM tbl_teacher as a , tbl_prefix as b WHERE a.PrefixCode=b.PrefixCode ORDER BY a.name DESC";
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "insertuserdata")) {
  $insertSQL = sprintf("INSERT INTO tbl_group (groupid, group_name, TeacherID, classlevelid) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['groupid'], "text"),
                       GetSQLValueString($_POST['group_name'], "text"),
                       GetSQLValueString($_POST['TeacherID'], "text"),
                       GetSQLValueString($_POST['classlevelid'], "int"));

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
<title>เพิ่มข้อมูลห้องเรียน</title>
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

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="insertuserdata" id="insertuserdata">
<div class="container">
 <a href="index.php" class="btn btn-primary">กลับไปหน้าข้อมูลห้องเรียน</a> 
 <h2 style="text-align:center;">เพิ่มข้อมูลห้องเรียน</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสห้องเรียน</td>
        <td><input name="groupid" type="text" required="required" class="form-control" id="groupid" placeholder="ใส่รหัสห้องเรียน เฉพาะตัวเลขเท่านั้น" size="3" maxlength="3" pattern="[0-9]{1,}" ></td>
      </tr>
      <tr>
        <td>ชื่อห้องเรียน</td>
        <td><input name="group_name" type="text" required="required" class="form-control" id="group_name" placeholder="ใส่ชื่อห้องเรียน" maxlength="30" ></td>
      </tr>
       
       <tr>
        <td>อาจารย์ที่รับผิดชอบ</td>
        <td><select name="TeacherID" id="TeacherID">
          <option value="">==เลือกชื่ออาจารย์ที่รับผิดชอบ==</option>
          <?php
do {  
?>
          <option value="<?php echo $row_teacher['TeacherID']?>"><?php echo $row_teacher['PrefixName']; ?> <?php echo $row_teacher['name']?> <?php echo $row_teacher['surname']; ?></option>
          <?php
} while ($row_teacher = mysqli_fetch_assoc($teacher));
  $rows = mysqli_num_rows($teacher);
  if($rows > 0) {
      mysqli_data_seek($teacher, 0);
	  $row_teacher = mysqli_fetch_assoc($teacher);
  }
?>
        </select>&nbsp;</td>
      </tr>
       <tr>
        <td>ระดับชั้น</td>
        <td><select name="classlevelid" required id="classlevelid">
          <option value="">==กรุณาเลือกระดับชั้น==</option>
          <?php
do {  
?>
          <option value="<?php echo $row_class['classlevelid']?>"><?php echo $row_class['classlevelname']?></option>
          <?php
} while ($row_class = mysqli_fetch_assoc($class));
  $rows = mysqli_num_rows($class);
  if($rows > 0) {
      mysqli_data_seek($class, 0);
	  $row_class = mysqli_fetch_assoc($class);
  }
?>
        </select>&nbsp;</td>
      </tr>
      </tr>
      
     
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="เพิ่มข้อมูลห้องเรียน" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertteacherdata">
<input type="hidden" name="MM_insert" value="insertuserdata">
</form>


</body>

</html>
<?php
mysqli_free_result($teacher);

mysqli_free_result($class);
?>
