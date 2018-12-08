<?php require_once('../Connections/stusystem.php'); ?>
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
$query_a1male = "SELECT * FROM tbl_student WHERE tbl_student.GenderCode ='1' and NOT tbl_student.statusid ='2' and tbl_student.GradeLevel='1'";
$a1male = mysqli_query($stusystem, $query_a1male) or die(mysqli_error($stusystem));
$row_a1male = mysqli_fetch_assoc($a1male);
$totalRows_a1male = mysqli_num_rows($a1male);

//42320819($database_stusystem, $stusystem);
$query_a1female = "SELECT * FROM tbl_student WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=1";
$a1female = mysqli_query($stusystem, $query_a1female) or die(mysqli_error($stusystem));
$row_a1female = mysqli_fetch_assoc($a1female);
$totalRows_a1female = mysqli_num_rows($a1female);

//42320819($database_stusystem, $stusystem);
$query_a2male = "SELECT * FROM tbl_student WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=2";
$a2male = mysqli_query($stusystem, $query_a2male) or die(mysqli_error($stusystem));
$row_a2male = mysqli_fetch_assoc($a2male);
$totalRows_a2male = mysqli_num_rows($a2male);

//42320819($database_stusystem, $stusystem);
$query_a2female = "SELECT * FROM tbl_student WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=2";
$a2female = mysqli_query($stusystem, $query_a2female) or die(mysqli_error($stusystem));
$row_a2female = mysqli_fetch_assoc($a2female);
$totalRows_a2female = mysqli_num_rows($a2female);

//42320819($database_stusystem, $stusystem);
$query_a3male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=3";
$a3male = mysqli_query($stusystem, $query_a3male) or die(mysqli_error($stusystem));
$row_a3male = mysqli_fetch_assoc($a3male);
$totalRows_a3male = mysqli_num_rows($a3male);

//42320819($database_stusystem, $stusystem);
$query_a3female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=3";
$a3female = mysqli_query($stusystem, $query_a3female) or die(mysqli_error($stusystem));
$row_a3female = mysqli_fetch_assoc($a3female);
$totalRows_a3female = mysqli_num_rows($a3female);

//42320819($database_stusystem, $stusystem);
$query_p1male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=4";
$p1male = mysqli_query($stusystem, $query_p1male) or die(mysqli_error($stusystem));
$row_p1male = mysqli_fetch_assoc($p1male);
$totalRows_p1male = mysqli_num_rows($p1male);

//42320819($database_stusystem, $stusystem);
$query_p1female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2  and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=4";
$p1female = mysqli_query($stusystem, $query_p1female) or die(mysqli_error($stusystem));
$row_p1female = mysqli_fetch_assoc($p1female);
$totalRows_p1female = mysqli_num_rows($p1female);

//42320819($database_stusystem, $stusystem);
$query_p2male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=5";
$p2male = mysqli_query($stusystem, $query_p2male) or die(mysqli_error($stusystem));
$row_p2male = mysqli_fetch_assoc($p2male);
$totalRows_p2male = mysqli_num_rows($p2male);

//42320819($database_stusystem, $stusystem);
$query_p2female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=5";
$p2female = mysqli_query($stusystem, $query_p2female) or die(mysqli_error($stusystem));
$row_p2female = mysqli_fetch_assoc($p2female);
$totalRows_p2female = mysqli_num_rows($p2female);

//42320819($database_stusystem, $stusystem);
$query_p3male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=6";
$p3male = mysqli_query($stusystem, $query_p3male) or die(mysqli_error($stusystem));
$row_p3male = mysqli_fetch_assoc($p3male);
$totalRows_p3male = mysqli_num_rows($p3male);

//42320819($database_stusystem, $stusystem);
$query_p3female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=6";
$p3female = mysqli_query($stusystem, $query_p3female) or die(mysqli_error($stusystem));
$row_p3female = mysqli_fetch_assoc($p3female);
$totalRows_p3female = mysqli_num_rows($p3female);

//42320819($database_stusystem, $stusystem);
$query_p4male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=7";
$p4male = mysqli_query($stusystem, $query_p4male) or die(mysqli_error($stusystem));
$row_p4male = mysqli_fetch_assoc($p4male);
$totalRows_p4male = mysqli_num_rows($p4male);

//42320819($database_stusystem, $stusystem);
$query_p4female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=7";
$p4female = mysqli_query($stusystem, $query_p4female) or die(mysqli_error($stusystem));
$row_p4female = mysqli_fetch_assoc($p4female);
$totalRows_p4female = mysqli_num_rows($p4female);

//42320819($database_stusystem, $stusystem);
$query_p5male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=8";
$p5male = mysqli_query($stusystem, $query_p5male) or die(mysqli_error($stusystem));
$row_p5male = mysqli_fetch_assoc($p5male);
$totalRows_p5male = mysqli_num_rows($p5male);

//42320819($database_stusystem, $stusystem);
$query_p5female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=8";
$p5female = mysqli_query($stusystem, $query_p5female) or die(mysqli_error($stusystem));
$row_p5female = mysqli_fetch_assoc($p5female);
$totalRows_p5female = mysqli_num_rows($p5female);

//42320819($database_stusystem, $stusystem);
$query_p6male = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =1 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=9";
$p6male = mysqli_query($stusystem, $query_p6male) or die(mysqli_error($stusystem));
$row_p6male = mysqli_fetch_assoc($p6male);
$totalRows_p6male = mysqli_num_rows($p6male);

//42320819($database_stusystem, $stusystem);
$query_p6female = "SELECT * FROM tbl_student  WHERE tbl_student.GenderCode =2 and NOT tbl_student.statusid =2 and tbl_student.GradeLevel=9";
$p6female = mysqli_query($stusystem, $query_p6female) or die(mysqli_error($stusystem));
$row_p6female = mysqli_fetch_assoc($p6female);
$totalRows_p6female = mysqli_num_rows($p6female);

 
$dataPoints1 = array(
	array("label"=> "อ.1", "y"=> $totalRows_a1male),
	array("label"=> "อ.2", "y"=> $totalRows_a2male),
	array("label"=> "อ.3", "y"=> $totalRows_a3male),
	array("label"=> "ป.1", "y"=> $totalRows_p1male),
	array("label"=> "ป.2", "y"=> $totalRows_p2male),
	array("label"=> "ป.3", "y"=> $totalRows_p3male),
	array("label"=> "ป.4", "y"=> $totalRows_p4male),
	array("label"=> "ป.5", "y"=> $totalRows_p5male),
	array("label"=> "ป.6", "y"=> $totalRows_p6male)
	
);
$dataPoints2 = array(
	array("label"=> "อ.1", "y"=> $totalRows_a1female),
	array("label"=> "อ.2", "y"=> $totalRows_a2female),
	array("label"=> "อ.3", "y"=> $totalRows_a3female),
	array("label"=> "ป.1", "y"=> $totalRows_p1female),
	array("label"=> "ป.2", "y"=> $totalRows_p2female),
	array("label"=> "ป.3", "y"=> $totalRows_p3female),
	array("label"=> "ป.4", "y"=> $totalRows_p4female),
	array("label"=> "ป.5", "y"=> $totalRows_p5female),
	array("label"=> "ป.6", "y"=> $totalRows_p6female)
	
);
	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">  
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "จำนวนนักเรียน แต่ละชั้นของ โรงเรียนอินทารามวิทยา"
	},
	legend:{
		cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "ชาย",
		indexLabel: "{y}",
		yValueFormatString: "#0.## คน",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
	},{
		type: "column",
		name: "หญิง",
		indexLabel: "{y}",
		yValueFormatString: "#0.## คน",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
<?php
mysqli_free_result($a1male);

mysqli_free_result($a1female);

mysqli_free_result($a2male);

mysqli_free_result($a2female);

mysqli_free_result($a3male);

mysqli_free_result($a3female);

mysqli_free_result($p1male);

mysqli_free_result($p1female);

mysqli_free_result($p2male);

mysqli_free_result($p2female);

mysqli_free_result($p3male);

mysqli_free_result($p3female);

mysqli_free_result($p4male);

mysqli_free_result($p4female);

mysqli_free_result($p5male);

mysqli_free_result($p5female);

mysqli_free_result($p6male);

mysqli_free_result($p6female);
?>
