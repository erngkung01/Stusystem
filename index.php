<?php require_once('Connections/stusystem.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=md5($_POST['password']);
  $MM_fldUserAuthorization = "usertypeid";
  $MM_redirectLoginSuccess = "Home.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = true;
  //dwthai($database_stusystem, $stusystem);
  
  $LoginRS__query=sprintf("SELECT username, password, usertypeid,user_name FROM tbl_user WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));
  
  $LoginRS = mysqli_query($stusystem, $LoginRS__query) or die(mysqli_error($stusystem));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $dwt_fetch=mysqli_fetch_assoc($LoginRS);
    $loginStrGroup  = $dwt_fetch['usertypeid'];
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
    $_SESSION['DWT_CODE']='dwthai_session';
	
	$sql = "INSERT INTO tbl_userlog (username,useraction) 
		VALUES ('".$_SESSION['MM_Username']."','LOG IN')";
	 $Result1 = mysqli_query($stusystem, $sql) or die(mysqli_error($stusystem));
	
    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }
    if( $_SESSION['MM_UserGroup'] =="555"){
	header("Location:student-page.php");
	
	
	}else{
		
		header("Location: " . $MM_redirectLoginSuccess );
		
		}
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ระบบหลังเว็บโรงเรียนอินทารามวิทยา</title>
<!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">
<?php if(!isset($_SESSION['MM_Username'])){?>
<form ACTION="<?php echo $loginFormAction; ?>" id="login" name="login" method="POST"  class="form-signin">
  <img class="mb-4" src="img/logo.jpg" alt="" width="250" height="250">
  <p>
  <h1 class="h3 mb-3 font-weight-normal">ระบบบริหารจัดการข้อมูลนักเรียนโรงเรียนอินทารามวิทยา</h1> <br>
    <label for="username">Username:</label>
    <input name="username" type="text" autofocus required class="form-control" id="username" placeholder="ใส่ Username" title="Username">
  </p>
  <p>
    <label for="password">Password:</label>
    <input name="password" type="password" required class="form-control" id="password" placeholder="ใส่ Password" title="Password">
  </p>
  <p>
    <input type="submit" name="Log In" id="Log In" value="เข้าสู่ระบบ" class="btn btn-lg btn-primary btn-block" >
  </p>
</form>
<?php }else{
	if( $_SESSION['MM_UserGroup'] =="555"){
	header("Location:student-page.php");
	
	}else{
		header("Location:Home.php"  );
		}
	}?>
</body>
</html>