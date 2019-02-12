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

$maxRows_teacher = 10;
$pageNum_teacher = 0;
if (isset($_GET['pageNum_teacher'])) {
  $pageNum_teacher = $_GET['pageNum_teacher'];
}
$startRow_teacher = $pageNum_teacher * $maxRows_teacher;

//42320819($database_stusystem, $stusystem);
$query_teacher = "SELECT a.*,b.* FROM tbl_teacher as a,tbl_prefix as b WHERE a.PrefixCode=b.PrefixCode ORDER BY a.TeacherID DESC";
$query_limit_teacher = sprintf("%s LIMIT %d, %d", $query_teacher, $startRow_teacher, $maxRows_teacher);
$teacher = mysqli_query($stusystem, $query_limit_teacher) or die(mysqli_error($stusystem));
$row_teacher = mysqli_fetch_assoc($teacher);

if (isset($_GET['totalRows_teacher'])) {
  $totalRows_teacher = $_GET['totalRows_teacher'];
} else {
  $all_teacher = mysqli_query($stusystem, $query_teacher);
  $totalRows_teacher = mysqli_num_rows($all_teacher);
}
$totalPages_teacher = ceil($totalRows_teacher/$maxRows_teacher)-1;

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
$query_Position = "SELECT * FROM tbl_position";
$Position = mysqli_query($stusystem, $query_Position) or die(mysqli_error($stusystem));
$row_Position = mysqli_fetch_assoc($Position);
$totalRows_Position = mysqli_num_rows($Position);

$queryString_teacher = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_teacher") == false && 
        stristr($param, "totalRows_teacher") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_teacher = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_teacher = sprintf("&totalRows_teacher=%d%s", $totalRows_teacher, $queryString_teacher);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบจัดการข้อมูลอาจารย์</title>
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<?php $page_active= 4 ;?>
<?php include("../head.php"); ?>
<div class="container">
	<h1>ระบบจัดการข้อมูลอาจารย์</h1>
     <div class="panel panel-primary">
      <div class="panel-heading"><h4>ค้นหาข้อมูล</h4></div>
      <div class="panel-body">
   <form action="search.php" method="get" id="search">

    <div class="col-sm-3"><select name="Prefix" id="Prefix" class="form-control">
      <option value="" >คำนำหน้าชื่อ</option>
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
    </select></div>
    <div class="col-sm-8">
    <input name="word" type="text" class="form-control" id="word" placeholder="ใส่ชื่อ / นามสกุลหรือรหัสอาจารย์" value="<?php echo $_GET['word']; ?>">
    </div>
    
      <div class="col-sm-12"><br></div>
    <div class="col-sm-3">
    <select name="Gender" class="form-control" id="Gender">
      <option value="" >เพศ</option>
      <?php
do {  
?>
<option value=""><?php echo $row_Gender['GenderName']?></option>
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
<select name="PositionID" class="form-control" id="PositionID">
  <option value="" >ตำแหน่ง</option>
  <?php
do {  
?>
  <option value="<?php echo $row_Position['PositionID']?>"><?php echo $row_Position['Positionname']?></option>
  <?php
} while ($row_Position = mysqli_fetch_assoc($Position));
  $rows = mysqli_num_rows($Position);
  if($rows > 0) {
      mysqli_data_seek($Position, 0);
	  $row_Position = mysqli_fetch_assoc($Position);
  }
?>
</select>
</div>
  
    
  <div class="col-sm-12"><br></div>
  <div class="col-sm-4">
  <!-- แสดงตัวเลือก จังหวัด -->
		  <select  id="province" name="province" class="form-control">
						  <option value="" >-- กรุณาเลือกจังหวัด --</option>
						 
          </select>
  </div>
  <div class="col-sm-4">
  <!-- แสดงตัวเลือก อำเภอ -->
		  <select  id="amphur" name="amphur" class="form-control">
						  <option value="" >-- กรุณาเลือกจังหวัดก่อน --</option>
						 
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
  <div class="col-sm-12">
  <div style="text-align:left;">
  <input name="search" type="submit" id="search" value="ค้นหา" class="btn btn-success">
  </div>
  <div style="text-align:right;">
  <a href="teacherqr/index.php" class="btn btn-success">ค้นหาโดย QRCode</a>
  </div>
  
  </div>
  </form>
    <br>
    
    </div>
    </div>
     <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
  <a href="insert.php" class="btn btn-success">เพิ่มข้อมูลอาจารย์</a><br>
  <?php } ?>
  <?php if ($totalRows_teacher > 0) { // Show if recordset not empty ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>รหัสอาจารย์</th>
        <th>ชื่อ</th>
        <th>นามสกุล</th>
        <th>จัดการข้อมูล</th>
        <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <th>ลบ</th>
        <?php } ?>
        </tr>
      </thead>
    <tbody>
      <?php do { ?>
      <tr>
        
        <td><?php echo $row_teacher['TeacherID']; ?></td>
        <td><?php echo $row_teacher['PrefixName']; ?><?php echo $row_teacher['name']; ?></td>
        <td><?php echo $row_teacher['surname']; ?></td>
        <td><a href="data.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-info">จัดการข้อมูล</a></td>
        <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
        <td><a href="delete.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-danger"onclick="return confirm('คุณแน่ใจที่จะลบข้อมูลนี้')">ลบ</a></td>
        
        <?php } ?>
        
        </tr>
      <?php } while ($row_teacher = mysqli_fetch_assoc($teacher)); ?>
      </tbody>
  </table>
  <ul class="pagination">
    <li><a href="<?php printf("%s?pageNum_teacher=%d%s", $currentPage, 0, $queryString_teacher); ?>">หน้าแรก</a></li>
    <li><a href="<?php printf("%s?pageNum_teacher=%d%s", $currentPage, max(0, $pageNum_teacher - 1), $queryString_teacher); ?>">หน้าก่อนหน้า</a></li>
    <?php $pid = $_GET["pageNum_teacher"];   ?>
    <?php  
   for($dw_i=0;$dw_i<=$totalPages_Teacher;$dw_i++){  ?>
    <li <?php if($pid==$dw_i){ ?> class="active" <?php } ?> ><a href="?pageNum_teacher=<?php echo $dw_i ; ?>"><?php echo ($dw_i+1); ?></a></li>
    <?php
   }
   ?>
    <li><a href="<?php printf("%s?pageNum_teacher=%d%s", $currentPage, min($totalPages_teacher, $pageNum_teacher + 1), $queryString_teacher); ?>">หน้าต่อไป</a></li>
    <li><a href="<?php printf("%s?pageNum_teacher=%d%s", $currentPage, $totalPages_teacher, $queryString_teacher); ?>">หน้าสุดท้าย</a></li>
  </ul>
  
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_teacher == 0) { // Show if recordset empty ?>
    ไม่พบข้อมูลอาจารย์
  <?php } // Show if recordset empty ?>
</div>
</body>
</html>
<?php
mysqli_free_result($teacher);

mysqli_free_result($Prefix);

mysqli_free_result($Gender);

mysqli_free_result($Position);
?>
