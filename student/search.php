<?php require_once('../Connections/stusystem.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Student = 50;
$pageNum_Student = 0;
if (isset($_GET['pageNum_Student'])) {
  $pageNum_Student = $_GET['pageNum_Student'];
}
$startRow_Student = $pageNum_Student * $maxRows_Student;

$colname_Student = "-1";
if (isset($_GET['word'])) {
  $colname_Student = $_GET['word'];
}
$colname2_Student = "''";
if (isset($_GET['word'])) {
  $colname2_Student = $_GET['word'];
}
$colname3_Student = "''";
if (isset($_GET['word'])) {
  $colname3_Student = $_GET['word'];
}
$colname4_Student = "-1";
if (isset($_GET['word'])) {
  $colname4_Student = $_GET['word'];
}

if(!empty($_GET['Prefix']) && $_GET['Prefix']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.PrefixCode='.$_GET['Prefix'];
	
	}
if(!empty($_GET['Gender']) && $_GET['Gender']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.GenderCode='.$_GET['Gender'];
	
	}
if(!empty($_GET['classlevelid']) && $_GET['classlevelid']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.GradeLevel='.$_GET['classlevelid'];
	
	}
if(!empty($_GET['groupid']) && $_GET['groupid']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.groupid='.$_GET['groupid'];
	
	}
if(!empty($_GET['status']) && $_GET['status']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'a.statusid='.$_GET['status'];
	
	}
if(!empty($_GET['province']) && $_GET['province']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'c.PROVINCE_ID='.$_GET['province'];
	
	}
if(!empty($_GET['amphur']) && $_GET['amphur']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'c.AMPHUR_ID='.$_GET['amphur'];
	
	}
if(!empty($_GET['district']) && $_GET['district']>0){ //คำนำหน้าชื่อ
	$arrWhere[]= 'c.DISTRICT_ID='.$_GET['district'];
	
	}
	if(!empty($arrWhere)){
	$strSQLWhere = implode(' and ', $arrWhere);
	if(count($arrWhere)>0){
		$strSQLWhere=' and '.$strSQLWhere;
		}
	
	}


//42320819($database_stusystem, $stusystem);
$query_Student = sprintf("SELECT a.*,b.*,c.* FROM tbl_student as a , tbl_prefix as b , tbl_address as c WHERE (a.studentID = %s or a.student_name like %s or a.student_surname like %s or a.PersonID=%s ) and( a.PrefixCode=b.PrefixCode and a.studentID=c.studentID) ".$strSQLWhere." ", GetSQLValueString($colname_Student, "text"),GetSQLValueString($colname2_Student . "%", "text"),GetSQLValueString($colname3_Student . "%", "text"),GetSQLValueString($colname4_Student, "text"));
$Student = mysqli_query($stusystem, $query_Student) or die(mysqli_error($stusystem));
$row_Student = mysqli_fetch_assoc($Student);
$totalRows_Student = mysqli_num_rows($Student);

//42320819($database_stusystem, $stusystem);
$query_Prefixcode = "SELECT * FROM tbl_prefix";
$Prefixcode = mysqli_query($stusystem, $query_Prefixcode) or die(mysqli_error($stusystem));
$row_Prefixcode = mysqli_fetch_assoc($Prefixcode);
$totalRows_Prefixcode = mysqli_num_rows($Prefixcode);

//42320819($database_stusystem, $stusystem);
$query_Gender = "SELECT * FROM tbl_gender";
$Gender = mysqli_query($stusystem, $query_Gender) or die(mysqli_error($stusystem));
$row_Gender = mysqli_fetch_assoc($Gender);
$totalRows_Gender = mysqli_num_rows($Gender);

//42320819($database_stusystem, $stusystem);
$query_studentstatus = "SELECT * FROM tbl_studentstatus";
$studentstatus = mysqli_query($stusystem, $query_studentstatus) or die(mysqli_error($stusystem));
$row_studentstatus = mysqli_fetch_assoc($studentstatus);
$totalRows_studentstatus = mysqli_num_rows($studentstatus);

//42320819($database_stusystem, $stusystem);
$query_classlevel = "SELECT * FROM tbl_classlevel";
$classlevel = mysqli_query($stusystem, $query_classlevel) or die(mysqli_error($stusystem));
$row_classlevel = mysqli_fetch_assoc($classlevel);
$totalRows_classlevel = mysqli_num_rows($classlevel);

//42320819($database_stusystem, $stusystem);
$query_group = "SELECT * FROM tbl_group";
$group = mysqli_query($stusystem, $query_group) or die(mysqli_error($stusystem));
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);

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
$query_district = "SELECT * FROM district";
$district = mysqli_query($stusystem, $query_district) or die(mysqli_error($stusystem));
$row_district = mysqli_fetch_assoc($district);
$totalRows_district = mysqli_num_rows($district);

$queryString_Student = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Student") == false && 
        stristr($param, "totalRows_Student") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Student = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Student = sprintf("&totalRows_Student=%d%s", $totalRows_Student, $queryString_Student);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลนักเรียน</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php $page_active= 1 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการข้อมูลนักเรียน</h1>
     <div class="panel panel-primary">
      <div class="panel-heading"><h4>ค้นหาข้อมูล</h4></div>
      <div class="panel-body">
   <form action="search.php" method="get" id="search">

    <div class="col-sm-3"><select name="Prefix" id="Prefix" class="form-control">
      <option value="" >คำนำหน้าชื่อ</option>
      <?php
do {  
?>
<option value="<?php echo $row_Prefixcode['PrefixCode']?>"><?php echo $row_Prefixcode['PrefixName']?></option>
      <?php
} while ($row_Prefixcode = mysqli_fetch_assoc($Prefixcode));
  $rows = mysqli_num_rows($Prefixcode);
  if($rows > 0) {
      mysqli_data_seek($Prefixcode, 0);
	  $row_Prefixcode = mysqli_fetch_assoc($Prefixcode);
  }
?>
    </select></div>
    <div class="col-sm-8">
    <input name="word" type="text" class="form-control" id="word" placeholder="ใส่ชื่อ / นามสกุลหรือรหัสนักเรียน" value="<?php echo $_GET['word']; ?>">
    </div>
    
      <div class="col-sm-12"><br></div>
    <div class="col-sm-3">
    <select name="Gender" class="form-control" id="Gender">
      <option value="">เพศ</option>
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
  </div>

  <div class="col-sm-3">
  <!-- แสดงตัวเลือก ระดับชั้น -->
            <select name="classlevelid"  id="classlevelid" class="form-control">
              <option value="">-- กรุณาเลือกระดับชั้น --</option>
              
          </select>
  </div>
  <div class="col-sm-3">
  <!-- แสดงตัวเลือก ห้อง -->
            <select name="groupid"   id="groupid" class="form-control">
              <option value="">-- กรุณาเลือกระดับชั้นก่อน --</option>
              <?php
do {  
?>
<option value="<?php echo $row_group['groupid']?>"><?php echo $row_group['group_name']?></option>
              <?php
} while ($row_group = mysqli_fetch_assoc($group));
  $rows = mysqli_num_rows($group);
  if($rows > 0) {
      mysqli_data_seek($group, 0);
	  $row_group = mysqli_fetch_assoc($group);
  }
?>
          </select>
          
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
  </div>
  <div class="col-sm-3">
  <select name="status" id="status" class="form-control">
    <option value="">สถาณะนักเรียน</option>
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
  </div>
  <div class="col-sm-12"><br></div>
  <div class="col-sm-4">
  <!-- แสดงตัวเลือก จังหวัด -->
						<select  id="province" name="province" class="form-control">
						  <option value="">-- กรุณาเลือกจังหวัด --</option>
						  
                        </select>
  </div>
  <div class="col-sm-4">
  <!-- แสดงตัวเลือก อำเภอ -->
						<select  id="amphur" name="amphur" class="form-control">
						  <option value="">-- กรุณาเลือกจังหวัดก่อน --</option>
						  
          </select>
  </div>
  <div class="col-sm-4">
  <!-- แสดงตัวเลือก ตำบล -->
						<select  id="district" name="district" class="form-control">
						  <option value="">-- กรุณาเลือกอำเภอก่อน --</option>
						 
                        </select>
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
				
				//ดึงข้อมูล province จากไฟล์ get_data.php
				$.ajax({
					url:"get_data2.php",
					dataType: "json", //กำหนดให้มีรูปแบบเป็น Json
					data:{show_province:'show_province'}, //ส่งค่าตัวแปร show_province เพื่อดึงข้อมูล จังหวัด
					success:function(data){
						
						//วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
						$.each(data, function( index, value ) {
							//แทรก Elements ใน id province  ด้วยคำสั่ง append
							  $("#province").append("<option value='"+ value.id +"'> " + value.name + "</option>");
						});
					}
				});
				
				
				//แสดงข้อมูล อำเภอ และ ตำบล  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
				$("#province").change(function(){

					//กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
					var province_id = $(this).val();
					
					$.ajax({
						url:"get_data2.php",
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
						url:"get_data2.php",
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
                        <br>     
                   
  </div>
  
 <div class="col-sm-12"><br></div>
  <div class="col-sm-1">
  <input name="search" type="submit" id="search" value="ค้นหา" class="btn btn-success">
  
  
  
  </div>
  </form>
    <br>
    <div style="text-align:right;">
  <a href="studentqr/index.php" class="btn btn-success">ค้นหาโดย QRCode</a>
  </div>
    </div>
    </div>
    
    <div class="col-sm-12"><br></div>
   <div class="col-sm-12">
     <?php if ($totalRows_Student > 0) { // Show if recordset not empty ?>
      <div class="alert alert-success">
   จำนวน  <strong style="color:#000DFF;"><?php echo $totalRows_Student ?></strong> คน 
</div>

  <?php } // Show if recordset not empty ?>
   </div>
   <div class="col-sm-12"></div>
   <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
  <a href="Studentdata/insert.php" class="btn btn-success">เพิ่มข้อมูลนักเรียน</a>
  <?php } ?>
  <br>
  <?php if ($totalRows_Student > 0) { // Show if recordset not empty ?>
   
    <table class="table table-hover">
    <thead>
      <tr>
        <th>รหัสนักเรียน</th>
        <th>ชื่อ</th>
        <th>นามสกุล</th>
        <th>จัดการข้อมูล</th>
        <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <th>ลบ</th>
        
        <?php  } ?>
        
        </tr>
      </thead>
    <tbody>
     <?php do { ?>
      <tr>
        <td><?php echo $row_Student['studentID']; ?></td>
        <td><?php echo $row_Student['PrefixName']; ?><?php echo $row_Student['student_name']; ?></td>
        <td><?php echo $row_Student['student_surname']; ?></td>
         <td><a class="btn btn-info" href="Studentdata/index.php?studentID=<?php echo $row_Student['studentID']; ?>">จัดการข้อมูล</a></td>
         <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
         <td><a href="Studentdata/delete.php?studentID=<?php echo $row_Student['studentID']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
         
         <?php } ?>
         
        </tr>
        <?php } while ($row_Student = mysqli_fetch_assoc($Student)); ?>
      </tbody>
  </table>
   <ul class="pagination">
     <li><a href="<?php printf("%s?pageNum_Student=%d%s", $currentPage, 0, $queryString_Student); ?>">หน้าแรก</a></li>
     <li><a href="<?php printf("%s?pageNum_Student=%d%s", $currentPage, max(0, $pageNum_Student - 1), $queryString_Student); ?>">หน้าก่อนหน้า</a></li>
     
   
   <?php $pid = $_GET["pageNum_Student"];   ?>
   <?php  
   for($dw_i=0;$dw_i<=$totalPages_Student;$dw_i++){  ?>
   
	   <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="?pageNum_Student=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li> 
   <?php
   }
   ?>
   <li><a href="<?php printf("%s?pageNum_Student=%d%s", $currentPage, min($totalPages_Student, $pageNum_Student + 1), $queryString_Student); ?>">หน้าต่อไป</a></li>
   <li><a href="<?php printf("%s?pageNum_Student=%d%s", $currentPage, $totalPages_Student, $queryString_Student); ?>">หน้าสุดท้าย</a></li>
   </ul>
    <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_Student == 0) { // Show if recordset empty ?>
      ไม่พบข้อมูลนักเรียน
  <?php } // Show if recordset empty ?>

</div>
</body>
</html>
<?php
mysqli_free_result($Student);

mysqli_free_result($Prefixcode);

mysqli_free_result($Gender);

mysqli_free_result($studentstatus);

mysqli_free_result($classlevel);

mysqli_free_result($group);

mysqli_free_result($province);

mysqli_free_result($amphur);

mysqli_free_result($district);
?>
