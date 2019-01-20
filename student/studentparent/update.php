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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updatestudentparent")) {
  $updateSQL = sprintf("UPDATE tbl_parent SET PersonID=%s, FatherPrefixCode=%s, FatherName=%s, FatherSurName=%s, Father_tel=%s, Fatheroccupa=%s, FatherSalary=%s, MatherPrefixCode=%s, Mathername=%s, Mathersurname=%s, MatherPersonID=%s, Mather_tel=%s, Matheroccupa=%s, MatherSalary=%s, ParentPrefixCode=%s, ParentName=%s, ParentSurname=%s, ParentPersonID=%s, Parent_tel=%s, Parentoccupa=%s, ParentSalary=%s WHERE studentID=%s AND FatherPersonID=%s",
                       GetSQLValueString($_POST['PersonID'], "text"),
                       GetSQLValueString($_POST['FatherPrefixCode'], "int"),
                       GetSQLValueString($_POST['FatherName'], "text"),
                       GetSQLValueString($_POST['FatherSurName'], "text"),
                       GetSQLValueString($_POST['Father_tel'], "text"),
                       GetSQLValueString($_POST['Fatheroccupa'], "text"),
                       GetSQLValueString($_POST['FatherSalary'], "text"),
                       GetSQLValueString($_POST['MatherPrefixCode'], "int"),
                       GetSQLValueString($_POST['Mathername'], "text"),
                       GetSQLValueString($_POST['Mathersurname'], "text"),
                       GetSQLValueString($_POST['MatherPersonID'], "text"),
                       GetSQLValueString($_POST['Mather_tel'], "text"),
                       GetSQLValueString($_POST['Matheroccupa'], "text"),
                       GetSQLValueString($_POST['MatherSalary'], "text"),
                       GetSQLValueString($_POST['ParentPrefixCode'], "int"),
                       GetSQLValueString($_POST['ParentName'], "text"),
                       GetSQLValueString($_POST['ParentSurname'], "text"),
                       GetSQLValueString($_POST['ParentPersonID'], "text"),
                       GetSQLValueString($_POST['Parent_tel'], "text"),
                       GetSQLValueString($_POST['Parentoccupa'], "text"),
                       GetSQLValueString($_POST['ParentSalary'], "text"),
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['FatherPersonID'], "text"));

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "index.php?studentID=<?php echo ".$row_student['studentID']."; ?>";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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
$query_prefix = "SELECT * FROM tbl_prefix";
$prefix = mysqli_query($stusystem, $query_prefix) or die(mysqli_error($stusystem));
$row_prefix = mysqli_fetch_assoc($prefix);
$totalRows_prefix = mysqli_num_rows($prefix);

//42320819($database_stusystem, $stusystem);
$query_parentstatus = "SELECT * FROM tbl_parentstatus";
$parentstatus = mysqli_query($stusystem, $query_parentstatus) or die(mysqli_error($stusystem));
$row_parentstatus = mysqli_fetch_assoc($parentstatus);
$totalRows_parentstatus = mysqli_num_rows($parentstatus);

$colname_studentparent = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentparent = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentparent = sprintf("SELECT * FROM tbl_parent WHERE studentID = %s", GetSQLValueString($colname_studentparent, "text"));
$studentparent = mysqli_query($stusystem, $query_studentparent) or die(mysqli_error($stusystem));
$row_studentparent = mysqli_fetch_assoc($studentparent);
$totalRows_studentparent = mysqli_num_rows($studentparent);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ปรับปรุงข้อมูลผู้ปกครองนักเรียน</title>
<link href="../../css/bootstrap.css" rel="stylesheet" type="text/css">
<script src="../../js/jquery-1.11.2.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
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

<?php include ("../../head.php");?>

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="updatestudentparent" id="updatestudentparent">
<div class="container">
 <a href="index.php?studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-primary">กลับไปหน้าข้อมูลผู้ปกครองนักเรียน</a> 
 <h2 style="text-align:center;">ปรับปรุงข้อมูลผู้ปกครองนักเรียน</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสนักเรียน</td>
        <td><input name="StudentID" type="text" required="required" class="form-control" id="StudentID" placeholder="ใส่รหัสนักเรียน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_student['studentID']; ?>" readonly></td>
      </tr>
      <tr>
        <td>ชื่อ</td>
        <td><input name="student_name" type="text" required="required" class="form-control" id="student_name" placeholder="ชื่อนามสกุลนักเรียน" value="<?php echo $row_student['student_name']; ?>" readonly> </td>
      </tr>
      <tr>
        <td>นามสกุล</td>
        <td><input name="studentname_surname" type="text" required="required" class="form-control" id="studentname_surname" placeholder="ชื่อนามสกุลนักเรียน" value="<?php echo $row_student['student_surname']; ?>" readonly></td>
      </tr>
       <tr>
        <td>รหัสประชาชน</td>
        <td><input name="PersonID" type="text" required="required" class="form-control" id="PersonID" placeholder="ใส่รหัสประชาชน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_student['PersonID']; ?>" readonly></td>
      </tr>
      
      <tr>
        <td>คำนำหน้าชื่อ-บิดา</td>
        <td><select name="FatherPrefixCode" autofocus id="FatherPrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_prefix['PrefixCode']?>"<?php if (!(strcmp($row_prefix['PrefixCode'], $row_studentparent['FatherPrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prefix['PrefixName']?></option>
          <?php
} while ($row_prefix = mysqli_fetch_assoc($prefix));
  $rows = mysqli_num_rows($prefix);
  if($rows > 0) {
      mysqli_data_seek($prefix, 0);
	  $row_prefix = mysqli_fetch_assoc($prefix);
  }
?>
        </select>          &nbsp;</td>
      </tr>
     <tr>
        <td>ชื่อ-บิดา</td>
        <td><input name="FatherName" type="text" class="form-control" id="FatherName" placeholder="ใส่ชื่อ-บิดา" value="<?php echo $row_studentparent['FatherName']; ?>"></td>
      </tr>
      <tr>
        <td>นามสกุล-บิดา</td>
        <td><input name="FatherSurName" type="text" class="form-control" id="FatherSurName" placeholder="ใส่นามสกุล-บิดา" value="<?php echo $row_studentparent['FatherSurName']; ?>"></td>
      </tr>
      
      <tr>
        <td>รหัสประชาชน-บิดา</td>
        <td><input name="FatherPersonID" type="text" class="form-control" id="FatherPersonID" placeholder="รหัสประชาชน-บิดา" value="<?php echo $row_studentparent['FatherPersonID']; ?>" maxlength="13" readonly></td>
      </tr>
     
      <tr>
        <td>เบอร์โทรศัพท์-บิดา</td>
        <td><input name="Father_tel" type="text" class="form-control" id="Father_tel" placeholder="ใส่เบอร์โทรศัพท์-บิดา" value="<?php echo $row_studentparent['Father_tel']; ?>"></td>
      </tr>
     
      <tr>
        <td>อาชีพ-บิดา</td>
        <td><input name="Fatheroccupa" type="text" class="form-control" id="Fatheroccupa" placeholder="ใส่อาชีพ-บิดา" value="<?php echo $row_studentparent['Fatheroccupa']; ?>"></td>
      </tr>
      <tr>
        <td>รายได้-บิดา</td>
        <td><input name="FatherSalary" type="number" class="form-control" id="FatherSalary" placeholder="ใส่รายได้-บิดา" value="<?php echo $row_studentparent['FatherSalary']; ?>"></td>
      </tr>
      <tr>
        <td>คำนำหน้าชื่อ-มารดา</td>
        <td><select name="MatherPrefixCode" id="MatherPrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_prefix['PrefixCode']?>"<?php if (!(strcmp($row_prefix['PrefixCode'], $row_studentparent['MatherPrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prefix['PrefixName']?></option>
          <?php
} while ($row_prefix = mysqli_fetch_assoc($prefix));
  $rows = mysqli_num_rows($prefix);
  if($rows > 0) {
      mysqli_data_seek($prefix, 0);
	  $row_prefix = mysqli_fetch_assoc($prefix);
  }
?>
        </select>          &nbsp;</td>
      </tr>
      <tr>
        <td>ชื่อ-มารดา</td>
        <td><input name="Mathername" type="text" class="form-control" id="Mathername" placeholder="ใส่ชื่อ-มารดา" value="<?php echo $row_studentparent['Mathername']; ?>"></td>
      </tr>
      <tr>
        <td>นามสกุล-มารดา</td>
        <td><input name="Mathersurname" type="text" class="form-control" id="Mathersurname" placeholder="ใส่นามสกุล-มารดา" value="<?php echo $row_studentparent['Mathersurname']; ?>"></td>
      </tr>
      
      
       <tr>
        <td>รหัสประชาชน-มารดา</td>
        <td><input name="MatherPersonID" type="text" class="form-control" id="MatherPersonID" placeholder="รหัสประชาชน-มารดา" value="<?php echo $row_studentparent['MatherPersonID']; ?>"  maxlength="13" readonly></td>
      </tr>
      
      <tr>
        <td>เบอร์โทรศัพท์-มารดา</td>
        <td><input name="Mather_tel" type="text" class="form-control" id="Mather_tel" placeholder="ใส่เบอร์โทรศัพท์-มารดา" value="<?php echo $row_studentparent['Mather_tel']; ?>"></td>
      </tr>
      <tr>
        <td>อาชีพ-มารดา</td>
        <td><input name="Matheroccupa" type="text" class="form-control" id="Matheroccupa" placeholder="ใส่อาชีพ-มารดา" value="<?php echo $row_studentparent['Matheroccupa']; ?>"></td>
      </tr>
      <tr>
        <td>รายได้-มารดา</td>
        <td><input name="MatherSalary" type="number" class="form-control" id="MatherSalary" placeholder="ใส่รายได้-มารดา" value="<?php echo $row_studentparent['MatherSalary']; ?>"></td>
      </tr>
      <tr>
        <td>คำนำหน้าชื่อ-ผู้ปกครอง</td>
        <td><select name="ParentPrefixCode" id="ParentPrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_prefix['PrefixCode']?>"<?php if (!(strcmp($row_prefix['PrefixCode'], $row_studentparent['ParentPrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prefix['PrefixName']?></option>
          <?php
} while ($row_prefix = mysqli_fetch_assoc($prefix));
  $rows = mysqli_num_rows($prefix);
  if($rows > 0) {
      mysqli_data_seek($prefix, 0);
	  $row_prefix = mysqli_fetch_assoc($prefix);
  }
?>
        </select>          &nbsp;</td>
      </tr>
      <tr>
        <td>ชื่อ-ผู้ปกครอง</td>
        <td><input name="ParentName" type="text" class="form-control" id="ParentName" placeholder="ใส่ชื่อ-ผู้ปกครอง" value="<?php echo $row_studentparent['ParentName']; ?>"></td>
      </tr>
      <tr>
        <td>นามสกุล-ผู้ปกครอง</td>
        <td><input name="ParentSurname" type="text" class="form-control" id="ParentSurname" placeholder="ใส่นามสกุล-ผู้ปกครอง" value="<?php echo $row_studentparent['ParentSurname']; ?>"></td>
      </tr>
      
       <tr>
        <td>รหัสประชาชน-ผู้ปกครอง</td>
        <td><input name="ParentPersonID" type="text" class="form-control" id="ParentPersonID" placeholder="รหัสประชาชน-ผู้ปกครอง" value="<?php echo $row_studentparent['ParentPersonID']; ?>" maxlength="13" readonly></td>
      </tr>
      
      <tr>
        <td>เบอร์โทรศัพท์-ผู้ปกครอง</td>
        <td><input name="Parent_tel" type="text" class="form-control" id="Parent_tel" placeholder="ใส่เบอร์โทรศัพท์-ผู้ปกครอง" value="<?php echo $row_studentparent['Parent_tel']; ?>"></td>
      </tr>
      <tr>
        <td>อาชีพ-ผู้ปกครอง</td>
        <td><input name="Parentoccupa" type="text" class="form-control" id="Parentoccupa" placeholder="ใส่อาชีพ-ผู้ปกครอง" value="<?php echo $row_studentparent['Parentoccupa']; ?>"></td>
      </tr>
      <tr>
        <td>รายได้-ผู้ปกครอง</td>
        <td><input name="ParentSalary" type="number" class="form-control" id="ParentSalary" placeholder="ใส่รายได้-ผู้ปกครอง" value="<?php echo $row_studentparent['ParentSalary']; ?>"></td>
      </tr>
     
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลที่อยู่นักเรียน" class="btn btn-success" >
        <input name="ParentID" type="hidden" id="ParentID" value="<?php echo $row_studentparent['ParentID']; ?>"></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_update" value="updatestudentparent">
</form>


</body>

</html>
<?php
mysqli_free_result($student);

mysqli_free_result($prefix);

mysqli_free_result($parentstatus);

mysqli_free_result($studentparent);
?>
