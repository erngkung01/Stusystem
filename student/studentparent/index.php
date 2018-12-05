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
$query_studentdata = sprintf("SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.* FROM tbl_student as a, tbl_prefix as b,tbl_gender as c,tbl_blger as d,tbl_religion as e,tbl_group as f,tbl_studentstatus as g,tbl_classlevel as h WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode and a.GenderCode=c.GenderCode and a.blgerid=d.blgerid and a.Religionid=e.Religionid and a.groupid=f.groupid and a.statusid=g.statusid and f.classlevelid=h.classlevelid", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.* FROM tbl_student as a, tbl_prefix as b WHERE a.studentID = %s and a.PrefixCode=b.PrefixCode ", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

$colname_studentparent = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentparent = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentparent = sprintf("SELECT * FROM tbl_parent WHERE studentID = %s", GetSQLValueString($colname_studentparent, "text"));
$studentparent = mysqli_query($stusystem, $query_studentparent) or die(mysqli_error($stusystem));
$row_studentparent = mysqli_fetch_assoc($studentparent);
$totalRows_studentparent = mysqli_num_rows($studentparent);

//42320819($database_stusystem, $stusystem);
$query_prefix = "SELECT * FROM tbl_prefix";
$prefix = mysqli_query($stusystem, $query_prefix) or die(mysqli_error($stusystem));
$row_prefix = mysqli_fetch_assoc($prefix);
$totalRows_prefix = mysqli_num_rows($prefix);

//42320819($database_stusystem, $stusystem);
$query_Matherprefix = "SELECT * FROM tbl_prefix";
$Matherprefix = mysqli_query($stusystem, $query_Matherprefix) or die(mysqli_error($stusystem));
$row_Matherprefix = mysqli_fetch_assoc($Matherprefix);
$totalRows_Matherprefix = mysqli_num_rows($Matherprefix);

//42320819($database_stusystem, $stusystem);
$query_parentprefix = "SELECT * FROM tbl_prefix";
$parentprefix = mysqli_query($stusystem, $query_parentprefix) or die(mysqli_error($stusystem));
$row_parentprefix = mysqli_fetch_assoc($parentprefix);
$totalRows_parentprefix = mysqli_num_rows($parentprefix);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการข้อมูลผู้ปกครองนักเรียน</title>
<link rel="stylesheet" href="../../css/bootstrap.css">
<script type="text/javascript" src="../../css/jquery.datetimepicker.css"></script>
<script type="text/javascript" src="../../js/bootstrap.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.2.min.js"></script>

</head>

<body>
<?php
$page_active= 3;
?>

<?php include ("../../head.php");?>
<!---เมนูหน้าข้อมูลนักเรียน--->
<?php include ("../student-menu.php");?>
<!--จบเมนูหน้าข้อมูลนักเรียน-->

<div class="col-sm-7">
<h1 style="text-align:center;">ข้อมูลผู้ปกครองนักเรียน</h1>
<h3 style="text-align:center;"><?php echo $row_studentdata['PrefixName']; ?> <?php echo $row_studentdata['student_name']; ?>  <?php echo $row_studentdata['student_surname']; ?></h3> <div style="text-align:right">
<?php if ($totalRows_studentparent > 0) { // Show if recordset not empty ?>
<a href="update.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-warning">แก้ไขข้อมูลผู้ปกครองนักเรียน</a>
<a href="../Studentdata/delete.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบข้อมูล</a>
</div>
<br>

  <table class="table table-hover">
    <tbody>
      <tr>
        <td>ชื่อ-นามสกุลบิดา</td>
        <td><?php do { ?>
          <?php 
		 if($row_prefix['PrefixCode']==$row_studentparent['FatherPrefixCode']){
			 echo $row_prefix['PrefixName'];
			 } ?>
          <?php } while ($row_prefix = mysqli_fetch_assoc($prefix)); ?> <?php echo $row_studentparent['FatherName']; ?> <?php echo $row_studentparent['FatherSurName']; ?></td>
      </tr>
      
      <tr>
        <td>เบอร์โทรศัพท์-บิดา </td>
        <td><?php echo $row_studentparent['Father_tel']; ?></td>
      </tr>
      <tr>
        <td>อาชีพ-บิดา </td>
        <td><?php echo $row_studentparent['Fatheroccupa']; ?></td>
      </tr>
      <tr>
        <td>รายได้บิดา </td>
        <td><?php echo $row_studentparent['FatherSalary']; ?></td>
      </tr>
      <tr>
        <td>ชื่อ-นามสกุลมารดา </td>
        <td><?php do { ?>
          <?php
		if($row_Matherprefix['PrefixCode']==$row_studentparent['MatherPrefixCode']){
			echo $row_Matherprefix['PrefixName'];
			}
		 ?>
          <?php } while ($row_Matherprefix = mysqli_fetch_assoc($Matherprefix)); ?> <?php echo $row_studentparent['Mathername']; ?> <?php echo $row_studentparent['Mathersurname']; ?></td>
      </tr>
      
      <tr>
        <td>เบอร์โทรศัพท์มารดา</td>
        <td><?php echo $row_studentparent['Mather_tel']; ?></td>
      </tr>
      <tr>
        <td>อาชีพ-มารดา</td>
        <td><?php echo $row_studentparent['Matheroccupa']; ?></td>
      </tr>
      <tr>
        <td>รายได้-มารดา</td>
        <td><?php echo $row_studentparent['MatherSalary']; ?></td>
      </tr>
      <tr>
        <td>ชื่อ-นามสกุลผู้ปกครอง</td>
        <td><?php do { ?>
          <?php 
			if($row_parentprefix['PrefixCode']==$row_studentparent['ParentPrefixCode']){
				echo $row_parentprefix['PrefixName'];
				}
		  ?>
          <?php } while ($row_parentprefix = mysqli_fetch_assoc($parentprefix)); ?> <?php echo $row_studentparent['ParentName']; ?> <?php echo $row_studentparent['ParentSurname']; ?></td>
      </tr>
     
      <tr>
        <td>เบอร์โทรศัพท์ผู้ปกครอง</td>
        <td><?php echo $row_studentparent['Parent_tel']; ?></td>
      </tr>
      <tr>
        <td>อาชีพ-ผู้ปกครอง</td>
        <td><?php echo $row_studentparent['Parentoccupa']; ?></td>
      </tr>
      <tr>
        <td>รายได้-ผู้ปกครอง</td>
        <td><?php echo $row_studentparent['ParentSalary']; ?></td>
      </tr>
    </tbody>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_studentparent == 0) { // Show if recordset empty ?>
<table class="table table-hover">
    <tbody>
      <tr>
        <td>ไม่มีข้อมูลครอบครัวนักเรียน</td>
        <td><a href="insert-not.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-danger">เพิ่มข้อมูลผู้ปกครองนักเรียน</a></td>
      </tr>
      
    </tbody>
  </table>
  
  <?php } // Show if recordset empty ?>
</div>

<div class="col-sm-2">

<?php include ("../student-menu2.php");?>

</div>

</body>
</html>
<?php
mysqli_free_result($studentdata);

mysqli_free_result($studentparent);

mysqli_free_result($prefix);

mysqli_free_result($Matherprefix);

mysqli_free_result($parentprefix);
?>
