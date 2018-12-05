<?php require_once('../../Connections/stusystem.php'); 
mysqli_select_db($database_stusystem);
$sql ="Select * From amphur Where PROVINCE_ID=".$_GET['pvid']." order by AMPHUR_NAME ASC";
$rs = mysqli_query($sql);
$strOption=null;
while($row=mysqli_fetch_assoc($rs)){
$strOption.='<option value="'.$row['AMPHUR_ID'].'">'.$row['AMPHUR_NAME'].'</option>';	
}
mysqli_close();
echo $strOption;


?>