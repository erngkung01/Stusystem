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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "insertteacherdata")) {
	
	
	if($_POST['WorkDate']!=''){
		$date = $_POST['WorkDate'];
		$show=explode("-",$date);
		$date1 = $show[0];
		$date2 = $show[1];
		$date3 = $show[2]-543;
		$dateval =  $date3."/".$date2."/".$date1;
		
		}else{
			$dateval= "0000/00/00";
			}
	
  $updateSQL = sprintf("UPDATE tbl_teacher SET PersonID=%s, PrefixCode=%s, name=%s, surname=%s, GenderCode=%s, WorkDate=%s, PositionID=%s, tel=%s, email=%s WHERE TeacherID=%s",
                       GetSQLValueString($_POST['PersonID'], "text"),
                       GetSQLValueString($_POST['PrefixCode'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['GenderCode'], "int"),
                       GetSQLValueString($dateval, "date"),
                       GetSQLValueString($_POST['PositionID'], "int"),
                       GetSQLValueString($_POST['tel'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['TeacherID'], "text"));

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "data.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

//42320819($database_stusystem, $stusystem);
$query_Position = "SELECT * FROM tbl_position";
$Position = mysqli_query($stusystem, $query_Position) or die(mysqli_error($stusystem));
$row_Position = mysqli_fetch_assoc($Position);
$totalRows_Position = mysqli_num_rows($Position);

$colname_teacher = "-1";
if (isset($_GET['TeacherID'])) {
  $colname_teacher = $_GET['TeacherID'];
}
//42320819($database_stusystem, $stusystem);
$query_teacher = sprintf("SELECT * FROM tbl_teacher WHERE TeacherID = %s", GetSQLValueString($colname_teacher, "text"));
$teacher = mysqli_query($stusystem, $query_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);
$totalRows_teacher = mysqli_num_rows($teacher);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ปรับปรุงข้อมูลที่อยู่นักเรียน</title>
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../js/jquery.datetimepicker.css">
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

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="insertteacherdata" id="insertteacherdata">
<div class="container">
 <a href="data.php" class="btn btn-primary">กลับไปหน้าข้อมูลอาจารย์</a> 
 <h2 style="text-align:center;">ปรับปรุงข้อมูลอาจารย์</h2>
     
  <table class="table table-condensed">
    
    <tbody>
      <tr>
        <td>รหัสอาจารย์</td>
        <td><input name="TeacherID" type="text" required="required" class="form-control" id="TeacherID" placeholder="ใส่รหัสอาจารย์ เฉพาะตัวเลขเท่านั้น"pattern="[0-9]{1,}" value="<?php echo $row_teacher['TeacherID']; ?>" readonly ></td>
      </tr>
      <tr>
        <td>รหัสประชาชน</td>
        <td><input name="PersonID" type="text" required="required" class="form-control" id="PersonID" placeholder="ใส่รหัสประชาชน เฉพาะตัวเลขเท่านั้น" pattern="[0-9]{1,}" value="<?php echo $row_teacher['PersonID']; ?>" readonly ></td>
      </tr>
       
       <tr>
        <td>คำนำหน้าชื่อ</td>
        <td><select name="PrefixCode" id="PrefixCode">
          <?php
do {  
?>
          <option value="<?php echo $row_prefix['PrefixCode']?>"<?php if (!(strcmp($row_prefix['PrefixCode'], $row_teacher['PrefixCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_prefix['PrefixName']?></option>
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
        <td><input name="name" type="text" required="required" class="form-control" id="name" placeholder="ใส่ชื่ออาจารย์" value="<?php echo $row_teacher['name']; ?>" ></td>
      </tr>
       <tr>
        <td>นามสกุล</td>
        <td><input name="surname" type="text" required="required" class="form-control" id="surname" placeholder="ใส่นามสกุลอาจารย์" value="<?php echo $row_teacher['surname']; ?>" ></td>
      </tr>
       
       <tr>
        <td>เพศ</td>
        <td><select name="GenderCode" id="GenderCode">
          <?php
do {  
?>
          <option value="<?php echo $row_Gender['GenderCode']?>"<?php if (!(strcmp($row_Gender['GenderCode'], $row_teacher['GenderCode']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Gender['GenderName']?></option>
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
      <td>วันที่เริ่มทำงาน</td>
      <td> <input type="text" name="WorkDate" id="WorkDate" value="<?php
		if($row_teacher['WorkDate']!=''){
		$date = $row_teacher['WorkDate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."-".$date2."-".$date1;
		echo $dateval;
		}
		
		 ?>"  class="form-control">&nbsp;</td>
        <!---Datepicker สคริป-->
      <script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
        
<script type="text/javascript" src="../js/jquery.datetimepicker.full.js"></script>


<script type="text/javascript">   
$(function(){
     
    $.datetimepicker.setLocale('th'); // ต้องกำหนดเสมอถ้าใช้ภาษาไทย และ เป็นปี พ.ศ.
     // กรณีใช้แบบ input
    $("#WorkDate").datetimepicker({
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
    $("#WorkDate").on("mouseenter mouseleave",function(e){
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
      <td>ตำแหน่ง</td>
      <td><select name="PositionID" id="PositionID">
        <?php
do {  
?>
        <option value="<?php echo $row_Position['PositionID']?>"<?php if (!(strcmp($row_Position['PositionID'], $row_teacher['PositionID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Position['Positionname']?></option>
        <?php
} while ($row_Position = mysqli_fetch_assoc($Position));
  $rows = mysqli_num_rows($Position);
  if($rows > 0) {
      mysqli_data_seek($Position, 0);
	  $row_Position = mysqli_fetch_assoc($Position);
  }
?>
      </select></td>
      </tr>
       <tr>
        <td>เบอร์โทรศัพท์</td>
        <td><input name="tel" type="text"  class="form-control" id="tel" placeholder="ใส่เบอร์โทรศัพท์" value="<?php echo $row_teacher['tel']; ?>" ></td>
      </tr>
       <tr>
        <td>E-mail </td>
        <td><input name="email" type="email"  class="form-control" id="email" placeholder="ใส่ E-mail" value="<?php echo $row_teacher['email']; ?>" ></td>
      </tr>
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลอาจารย์" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_insert" value="insertteacherdata">
<input type="hidden" name="MM_update" value="insertteacherdata">
</form>


</body>

</html>
<?php
mysqli_free_result($prefix);

mysqli_free_result($Gender);

mysqli_free_result($Position);

mysqli_free_result($teacher);
?>
