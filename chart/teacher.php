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
$query_male = "SELECT * FROM tbl_teacher WHERE tbl_teacher.GenderCode=1";
$male = mysqli_query($stusystem, $query_male) or die(mysqli_error($stusystem));
$row_male = mysqli_fetch_assoc($male);
$totalRows_male = mysqli_num_rows($male);

//42320819($database_stusystem, $stusystem);
$query_female = "SELECT * FROM tbl_teacher WHERE tbl_teacher.GenderCode=2";
$female = mysqli_query($stusystem, $query_female) or die(mysqli_error($stusystem));
$row_female = mysqli_fetch_assoc($female);
$totalRows_female = mysqli_num_rows($female);

$teachertotal = $totalRows_female+$totalRows_male ;
$dataPoints = array( 
	array("label"=>"ชาย", "y"=>$totalRows_male),
	array("label"=>"หญิง", "y"=>$totalRows_female),
)
 
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>ครูทั้งหมด</title>
<script>
window.onload = function() {
 
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "จำนวนครูทั้งหมด"
	},
	subtitles: [{
		text: "โรงเรียนอินทารามวิทยา"
	}],
	data: [{
		type: "pie",
		yValueFormatString: "#,##0\" คน\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<br>
จำนวนครูทั้งหมด <?php echo $teachertotal ?> คน

</body>
</html>
<?php
mysqli_free_result($male);

mysqli_free_result($female);
?>
