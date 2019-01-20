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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "insertstudentaddress")) {
  $updateSQL = sprintf("UPDATE tbl_address SET PersonID=%s, HouseNo=%s, moo=%s, street=%s, DISTRICT_ID=%s, AMPHUR_ID=%s, PROVINCE_ID=%s, ZipCode=%s, stu_gps=%s WHERE studentID=%s AND Homeid=%s",
                       GetSQLValueString($_POST['PersonID'], "text"),
                       GetSQLValueString($_POST['HouseNo'], "text"),
                       GetSQLValueString($_POST['moo'], "text"),
                       GetSQLValueString($_POST['street'], "text"),
                       GetSQLValueString($_POST['district'], "int"),
                       GetSQLValueString($_POST['amphur'], "int"),
                       GetSQLValueString($_POST['province'], "int"),
                       GetSQLValueString($_POST['ZipCode'], "text"),
                       GetSQLValueString($_POST['stu_gps'], "text"),
                       GetSQLValueString($_POST['StudentID'], "text"),
                       GetSQLValueString($_POST['Homeid'], "text"));

  //mysql_select_db($database_stusystem, $stusystem);
  $Result1 = mysqli_query($stusystem, $updateSQL) or die(mysqli_error($stusystem));

  $updateGoTo = "index.php";
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

$colname_studentaddress = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentaddress = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaddress = sprintf("SELECT * FROM tbl_address WHERE studentID = %s", GetSQLValueString($colname_studentaddress, "text"));
$studentaddress = mysqli_query($stusystem, $query_studentaddress) or die(mysqli_error($stusystem));
$row_studentaddress = mysqli_fetch_assoc($studentaddress);
$totalRows_studentaddress = mysqli_num_rows($studentaddress);

//42320819($database_stusystem, $stusystem);
$query_province = "SELECT * FROM province";
$province = mysqli_query($stusystem, $query_province) or die(mysqli_error($stusystem));
$row_province = mysqli_fetch_assoc($province);
$totalRows_province = mysqli_num_rows($province);

//42320819($database_stusystem, $stusystem);
$query_amphur = "SELECT * FROM amphur";
$amphur = mysqli_query($stusystem, $query_amphur) or die(mysqli_error($stusystem));
$row_amphur = mysqli_fetch_assoc($amphur);
$totalRows_amphur = mysqli_num_rows($amphur);

//42320819($database_stusystem, $stusystem);
$query_distic = "SELECT * FROM district";
$distic = mysqli_query($stusystem, $query_distic) or die(mysqli_error($stusystem));
$row_distic = mysqli_fetch_assoc($distic);
$totalRows_distic = mysqli_num_rows($distic);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ปรับปรุงข้อมูลที่อยู่นักเรียน</title>
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

<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="insertstudentaddress" id="insertstudentaddress">
<div class="container">
 <a href="index.php?studentID=<?php echo $row_student['studentID']; ?>" class="btn btn-primary">กลับไปหน้าข้อมูลที่อยู่นักเรียน</a> 
 <h2 style="text-align:center;">ปรับปรุงข้อมูลที่อยู่นักเรียน</h2>
     
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
        <td>รหัสประจำบ้าน</td>
        <td><input name="Homeid" type="text" autofocus required="required" class="form-control" id="Homeid" placeholder="ใส่รหัสบ้าน" value="<?php echo $row_studentaddress['Homeid']; ?>" maxlength="11" readonly></td>
      </tr>
      <tr>
        <td>เลขที่บ้าน</td>
        <td><input name="HouseNo" type="text" autofocus required="required" class="form-control" id="HouseNo" placeholder="ใส่บ้านเลขที่" value="<?php echo $row_studentaddress['HouseNo']; ?>"></td>
      </tr>
      <tr>
        <td>หมู่</td>
        <td><input name="moo" type="number" class="form-control" id="moo" placeholder="ใส่หมู่" pattern="[0-9]{1,}" value="<?php echo $row_studentaddress['moo']; ?>" ></td>
      </tr>
      <tr>
        <td>ถนน</td>
        <td><input name="street" type="text"   class="form-control" id="street" placeholder="ใส่ชื่อถนน" value="<?php echo $row_studentaddress['street']; ?>"  ></td>
      </tr>
       <tr>
        <td>จังหวัด</td>
        <td><!-- แสดงตัวเลือก จังหวัด -->
						<select  id="province" name="province">
						  <option value="1" <?php if (!(strcmp(1, $row_studentaddress['PROVINCE_ID']))) {echo "selected=\"selected\"";} ?>>-- กรุณาเลือกจังหวัด --</option>
						  <?php
do {  
?>
						  <option value="<?php echo $row_province['PROVINCE_ID']?>"<?php if (!(strcmp($row_province['PROVINCE_ID'], $row_studentaddress['PROVINCE_ID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_province['PROVINCE_NAME']?></option>
						  <?php
} while ($row_province = mysqli_fetch_assoc($province));
  $rows = mysqli_num_rows($province);
  if($rows > 0) {
      mysqli_data_seek($province, 0);
	  $row_province = mysqli_fetch_assoc($province);
  }
?>
                        </select>
                        
                        </td>
      </tr>
       <tr>
        <td>อำเภอ</td>
        <td><!-- แสดงตัวเลือก อำเภอ -->
						<select  id="amphur" name="amphur">
						  <option value="1" <?php if (!(strcmp(1, $row_studentaddress['AMPHUR_ID']))) {echo "selected=\"selected\"";} ?>>-- กรุณาเลือกจังหวัดก่อน --</option>
						  <?php
do {  
?>
						  <option value="<?php echo $row_amphur['AMPHUR_ID']?>"<?php if (!(strcmp($row_amphur['AMPHUR_ID'], $row_studentaddress['AMPHUR_ID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_amphur['AMPHUR_NAME']?></option>
						  <?php
} while ($row_amphur = mysqli_fetch_assoc($amphur));
  $rows = mysqli_num_rows($amphur);
  if($rows > 0) {
      mysqli_data_seek($amphur, 0);
	  $row_amphur = mysqli_fetch_assoc($amphur);
  }
?>
                        </select>
                        
                        </td>
      </tr>
       <tr>
        <td>ตำบล</td>
        <td><!-- แสดงตัวเลือก ตำบล -->
						<select  id="district" name="district">
						  <option value="1" <?php if (!(strcmp(1, $row_studentaddress['DISTRICT_ID']))) {echo "selected=\"selected\"";} ?>>-- กรุณาเลือกอำเภอก่อน --</option>
						  <?php
do {  
?>
						  <option value="<?php echo $row_distic['DISTRICT_ID']?>"<?php if (!(strcmp($row_distic['DISTRICT_ID'], $row_studentaddress['DISTRICT_ID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_distic['DISTRICT_NAME']?></option>
						  <?php
} while ($row_distic = mysqli_fetch_assoc($distic));
  $rows = mysqli_num_rows($distic);
  if($rows > 0) {
      mysqli_data_seek($distic, 0);
	  $row_distic = mysqli_fetch_assoc($distic);
  }
?>
            </select></td>
                        
                        <!----เริ่มสคริปจังหวัดอำเภอตำบล---->
                        <!-- นำเข้า Javascript jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	
	<script>
			
			$(function(){
				
				//เรียกใช้งาน Select2
				$(".select2-single").select2();
				
				
				
				
				//แสดงข้อมูล อำเภอ และ ตำบล  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#province").change(function(){

					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var province_id = $(this).val();
					
					$.ajax({
						url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{province_id:province_id},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
						success:function(data){
							
							//กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
							$("#amphur").text("");
							
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data  
							$.each(data, function( index, value ) {
								
								//แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
								  $("#amphur").append("<option value='"+ value.id +"'> " + value.name + "</option>");
							});
						}
					});
					
					//กำหนดให้ ตัวแปร amphur_id มีค่าเท่ากับ ค่าของ  #amphur ที่กำลังถูกเลือกในขณะนั้น
					var amphur_id = $(this).val();
					
					$.ajax({
						url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{amphur_id:amphur_id},//ส่งค่าตัวแปร amphur_id เพื่อดึงข้อมูล ตำบล ที่มี amphur_id เท่ากับค่าที่ส่งไป
						success:function(data){
							
							  //กำหนดให้ข้อมูลใน #district เป็นค่าว่าง
							  $("#district").text("");
							  
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data  
							$.each(data, function( index, value ) {
								
							  //แทรก Elements ข้อมูลที่ได้  ใน id district  ด้วยคำสั่ง append
							  $("#district").append("<option value='" + value.id + "'> " + value.name + "</option>");
							  
							});
						}
					});

				});
				
				//แสดงข้อมูลตำบล โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่  #amphur
				$("#amphur").change(function(){
					
					//กำหนดให้ ตัวแปร amphur_id มีค่าเท่ากับ ค่าของ  #amphur ที่กำลังถูกเลือกในขณะนั้น
					var amphur_id = $(this).val();
					
					$.ajax({
						url:"get_data.php",
						dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
						data:{amphur_id:amphur_id},//ส่งค่าตัวแปร amphur_id เพื่อดึงข้อมูล ตำบล ที่มี amphur_id เท่ากับค่าที่ส่งไป
						success:function(data){
							
							  //กำหนดให้ข้อมูลใน #district เป็นค่าว่าง
							  $("#district").text("");
							  
							//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data  
							$.each(data, function( index, value ) {
								
							  //แทรก Elements ข้อมูลที่ได้  ใน id district  ด้วยคำสั่ง append
							  $("#district").append("<option value='" + value.id + "'> " + value.name + "</option>");
							  
							});
						}
					});
					
				});
				
				//คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่  #district 
				$("#district").change(function(){
					
					//นำข้อมูลรายการ จังหวัด ที่เลือก มาใส่ไว้ในตัวแปร province
					var province = $("#province option:selected").text();
					
					//นำข้อมูลรายการ อำเภอ ที่เลือก มาใส่ไว้ในตัวแปร amphur
					var amphur = $("#amphur option:selected").text();
					
					//นำข้อมูลรายการ ตำบล ที่เลือก มาใส่ไว้ในตัวแปร district
					var district = $("#district option:selected").text();
					
					
					
				});
				
				
			});
			
	</script>
                        
                        <!----จบ สคริปจังหวัดอำเภอตำบล---->
                        
      </tr>
      <tr>
        <td>รหัสไปรษณีย์</td>
        <td><input name="ZipCode" type="text" class="form-control" id="ZipCode" placeholder="ใส่รหัสไปรษณีย์" pattern="[0-9]{1,}" value="<?php echo $row_studentaddress['ZipCode']; ?>" ></td>
      </tr>
        <td>แผนที่บ้านนักเรียน</td>
        <td><input name="stu_gps" type="text" required="required" class="form-control" id="stu_gps" placeholder="ใส่ URL Google Map" value="<?php echo $row_studentaddress['stu_gps']; ?>">
          <br>
          <div style="color:#FC0F13;">*หมายเหตุ : สามารถ COPYลิงค์แชร์แบบ Embed จาก Googlemap มาใช้ได้เลย หรือถ้าไม่มีให้ใส่ "-" ไว้ <a href="https://www.google.co.th/maps">คลิกที่นี่</a></div></td>
      </tr>
     
      
      <td colspan="2" style="text-align:center;"><input name="inserstudentdata" type="submit" id="inserstudentdata" value="ปรับปรุงข้อมูลที่อยู่นักเรียน" class="btn btn-success" ></td>
        </tr>
    </tbody>
  </table>
</div>
<input type="hidden" name="MM_update" value="insertstudentaddress">
</form>


</body>

</html>
<?php
mysqli_free_result($student);

mysqli_free_result($studentaddress);

mysqli_free_result($province);

mysqli_free_result($amphur);

mysqli_free_result($distic);
?>
