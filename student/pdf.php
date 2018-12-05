<?php
include_once 'vendor/autoload.php';

$mpdf = new mPDF();

$colname_studentdata = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentdata = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentdata = sprintf("SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.*,i.*,j.* FROM tbl_student as a left join tbl_prefix as b on a.PrefixCode=b.PrefixCode left join tbl_gender as c on  a.GenderCode=c.GenderCode left join tbl_blger as d on  a.blgerid=d.blgerid  left join tbl_religion as e on  a.Religionid=e.Religionid left join tbl_group as f on a.groupid=f.groupid  left join tbl_studentstatus as g on  a.statusid=g.statusid left join tbl_classlevel as h on  a.GradeLevel=h.classlevelid left join tbl_academicyears as i on  a.AcademicYears=i.AcademicYearsID left join tbl_semester as j on  a.Semester=j.SemesterID WHERE a.studentID = %s and  (a.PrefixCode=b.PrefixCode or b.PrefixCode is null ) and  (a.GenderCode=c.GenderCode or c.GenderCode is null ) and  (a.blgerid=d.blgerid or d.blgerid is null ) and ( a.Religionid=e.Religionid or e.Religionid is null ) and  (a.groupid=f.groupid or f.groupid is null ) and ( a.statusid=g.statusid or g.statusid is null  ) and ( a.GradeLevel=h.classlevelid or h.classlevelid is null ) and ( a.AcademicYears=i.AcademicYearsID or i.AcademicYearsID is null )  and ( a.Semester=j.SemesterID or j.SemesterID is null  ) ", GetSQLValueString($colname_studentdata, "text"));
$studentdata = mysqli_query($stusystem, $query_studentdata) or die(mysqli_error($stusystem));
$row_studentdata = mysqli_fetch_assoc($studentdata);
$totalRows_studentdata = mysqli_num_rows($studentdata);

$colname_studentaddress = "-1";
if (isset($_GET['studentID'])) {
  $colname_studentaddress = $_GET['studentID'];
}
//42320819($database_stusystem, $stusystem);
$query_studentaddress = sprintf("SELECT a.*,b.*,c.*,d.* FROM tbl_address as a left join  province as b on a.PROVINCE_ID=b.PROVINCE_ID left join  district as c on a.DISTRICT_ID=c.DISTRICT_ID left join  amphur as d on a.AMPHUR_ID=d.AMPHUR_ID WHERE a.studentID = %s and  (a.PROVINCE_ID=b.PROVINCE_ID or b.PROVINCE_ID  is null  ) and  (a.DISTRICT_ID=c.DISTRICT_ID or c.DISTRICT_ID is null  ) and  (a.AMPHUR_ID=d.AMPHUR_ID or d.AMPHUR_ID is null  )", GetSQLValueString($colname_studentaddress, "text"));
$studentaddress = mysqli_query($stusystem, $query_studentaddress) or die(mysqli_error($stusystem));
$row_studentaddress = mysqli_fetch_assoc($studentaddress);
$totalRows_studentaddress = mysqli_num_rows($studentaddress);

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
$query_entrysemmester = "SELECT * FROM tbl_academicyears";
$entrysemmester = mysqli_query($stusystem, $query_entrysemmester) or die(mysqli_error($stusystem));
$row_entrysemmester = mysqli_fetch_assoc($entrysemmester);
$totalRows_entrysemmester = mysqli_num_rows($entrysemmester);

//42320819($database_stusystem, $stusystem);
$query_academicyear = "SELECT * FROM tbl_academicyears";
$academicyear = mysqli_query($stusystem, $query_academicyear) or die(mysqli_error($stusystem));
$row_academicyear = mysqli_fetch_assoc($academicyear);
$totalRows_academicyear = mysqli_num_rows($academicyear);

//42320819($database_stusystem, $stusystem);
$query_fatherprefix = "SELECT * FROM tbl_prefix";
$fatherprefix = mysqli_query($stusystem, $query_fatherprefix) or die(mysqli_error($stusystem));
$row_fatherprefix = mysqli_fetch_assoc($fatherprefix);
$totalRows_fatherprefix = mysqli_num_rows($fatherprefix);

//42320819($database_stusystem, $stusystem);
$query_matherprefix = "SELECT * FROM tbl_prefix";
$matherprefix = mysqli_query($stusystem, $query_matherprefix) or die(mysqli_error($stusystem));
$row_matherprefix = mysqli_fetch_assoc($matherprefix);
$totalRows_matherprefix = mysqli_num_rows($matherprefix);

//42320819($database_stusystem, $stusystem);
$query_prefixparent = "SELECT * FROM tbl_prefix";
$prefixparent = mysqli_query($stusystem, $query_prefixparent) or die(mysqli_error($stusystem));
$row_prefixparent = mysqli_fetch_assoc($prefixparent);
$totalRows_prefixparent = mysqli_num_rows($prefixparent);


$content = '

<style>
.container{
    font-family: "Garuda";
    font-size: 12pt;
}
p{
    text-align: justify;
}
h1{
    text-align: center;
}
</style>
<link rel="stylesheet" href="../css/bootstrap.css">

<div class="col-sm-12"></div>
<div class="col-sm-12"></div>
<div class="col-sm-12"></div>
<div class="col-sm-12"></div>
<div class="col-sm-12"></div>
<div class="col-xs-3"><img src="../img/logo.jpg" width="150" height="150" alt=""/></div>
<div class="col-xs-6" style="text-align:center; font-size:25px;"><br>โรงเรียนอินทารามวิทยา</div>
<div class="col-xs-3"><img src="Studentdata/images/<?php echo $row_studentdata['student_img']; ?>" id="" height="150" width="150" alt=""></div>
<div class="col-xs-12"></div>
<div class="col-xs-12" style="text-align:center; font-size:20px;">ข้อมูลส่วนตัว</div>
<div class="col-xs-12"></div>
<div class="col-xs-12"></div>
<div class="col-xs-12"></div>
<div class="col-xs-12"></div>
<div class="col-xs-12"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:13px;">
  <tbody>
    <tr>
      <td><strong>รหัสนักเรียน</strong> <?php echo $row_studentdata['studentID']; ?>&nbsp;</td>
      <td><strong>รหัสประชาชน</strong> <?php echo $row_studentdata['PersonID']; ?>&nbsp;</td>
      <td><strong>ชื่อ</strong> <?php echo $row_studentdata['PrefixName']; ?><?php echo $row_studentdata['student_name']; ?> &nbsp;</td>
      <td><strong>นามสกุล</strong> <?php echo $row_studentdata['student_surname']; ?></td>
     
      </tr>
      <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
    <tr>
     <td><strong>เพศ</strong> <?php echo $row_studentdata['GenderName']; ?>&nbsp;</td>
      <td><strong>หมู่เลือด</strong> <?php echo $row_studentdata['blgerName']; ?>&nbsp;</td>
      <td><strong>วัน/เดือน/ปีเกิด</strong>
<?php
		if($row_studentdata['student_Birthdate']!=''){
		$date = $row_studentdata['student_Birthdate'];
		$show=explode("-",$date);
		$date1 = $show[0]+543;
		$date2 = $show[1];
		$date3 = $show[2];
		$dateval =  $date3."/".$date2."/".$date1;
		echo $dateval;
		}
		
		 ?>&nbsp;</td>
      <td>ชื่อเล่น  <?php echo $row_studentdata['Student_Nickname']; ?>&nbsp;</td>
     
      </tr>
    
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
     <td>ศาสนา  <?php echo $row_studentdata['Religionname']; ?>&nbsp;</td>
      <td><strong>ปีการศึกษาที่เข้าเรียน</strong>
<?php do { ?>
        <?php if($row_studentdata['EntryAcademicYear']==$row_entrysemmester['AcademicYearsID']){
		  echo $row_entrysemmester['AcademicYears'];
		  } ?>
        <?php } while ($row_entrysemmester = mysqli_fetch_assoc($entrysemmester)); ?>        &nbsp;
      </td>
      <td><strong>ปีการศึกษา</strong> <?php echo $row_studentdata['AcademicYears']; ?>
      
      &nbsp;</td>
      <td><strong>ระดับชั้น </strong><?php echo $row_studentdata['classlevelname']; ?>&nbsp;</td>
     
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
       <td><strong>ห้องเรียน </strong><?php echo $row_studentdata['group_name']; ?>&nbsp;</td>
      <td><strong>ภาคเรียน </strong><?php echo $row_studentdata['Semester']; ?>&nbsp;</td>
      <td><strong>สถาณะนักเรียน </strong><?php echo $row_studentdata['Statusname']; ?>&nbsp;</td>
      <td><strong>เบอร์โทรศัพท์ </strong><?php echo $row_studentdata['stu_tel']; ?>&nbsp;</td>
      
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="font-size:20px;">ที่อยู่&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>บ้านเลขที่</strong>  <?php echo $row_studentaddress['Homeid']; ?>&nbsp;</td>
      <td><strong>หมู่ </strong> <?php echo $row_studentaddress['moo']; ?>&nbsp;</td>
      <td><strong>ถนน </strong><?php echo $row_studentaddress['street']; ?>&nbsp;</td>
      <td><strong>ตำบล</strong> <?php echo $row_studentaddress['DISTRICT_NAME']; ?>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>อำเภอ</strong> <?php echo $row_studentaddress['AMPHUR_NAME']; ?>&nbsp;</td>
      <td><strong>จังหวัด</strong> <?php echo $row_studentaddress['PROVINCE_NAME']; ?>&nbsp;</td>
      <td><strong>รหัสไปรษณีย์</strong> <?php echo $row_studentaddress['ZipCode']; ?>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
       <td style="font-size:20px;">ผู้ปกครอง&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>ชื่อ-บิดา
        <?php do { ?>
      <?php if($row_studentparent['FatherPrefixCode']==$row_fatherprefix['PrefixCode']){  echo $row_fatherprefix['PrefixName'];} ?>
      <?php } while ($row_fatherprefix = mysqli_fetch_assoc($fatherprefix));?><?php echo $row_studentparent['FatherName']; ?>        &nbsp;</td>
      <td>นามสกุล - บิดา <?php echo $row_studentparent['FatherSurName']; ?></td>
      <td>เบอร์โทรศัพท์-บิดา <?php echo $row_studentparent['Father_tel']; ?>&nbsp;</td>
      <td>อาชีพ-บิดา <?php echo $row_studentparent['Fatheroccupa']; ?>&nbsp;</td>
      
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td>รายได้-บิดา <?php echo $row_studentparent['FatherSalary']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>ชื่อ-มารดา&nbsp;
        <?php do { ?>
      <?php if($row_studentparent['MatherPrefixCode']==$row_matherprefix['PrefixCode']){echo $row_matherprefix['PrefixName'];} ?>
      <?php } while ($row_matherprefix = mysqli_fetch_assoc($matherprefix)); ?><?php echo $row_studentparent['Mathername']; ?></td>
      <td>นามสกุล-มารดา&nbsp; <?php echo $row_studentparent['Mathersurname']; ?> </td>
      <td>เบอร์โทรศัพท์-มารดา <?php echo $row_studentparent['Mather_tel']; ?>&nbsp;</td>
      <td>อาชีพ-มารดา <?php echo $row_studentparent['Matheroccupa']; ?>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>รายได้-มารดา <?php echo $row_studentparent['MatherSalary']; ?>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>ชื่อ-ผู้ปกครอง&nbsp;
        <?php do { ?>
      <?php if($row_studentparent['ParentPrefixCode']==$row_prefixparent['PrefixCode']){echo $row_prefixparent['PrefixName'];} ?>
      <?php } while ($row_prefixparent = mysqli_fetch_assoc($prefixparent)); ?><?php echo $row_studentparent['ParentName']; ?></td>
      <td>นามสกุล-ผู้ปกครอง&nbsp; <?php echo $row_studentparent['ParentSurname']; ?></td>
      <td>เบอร์โทรศัพท์-ผู้ปกครอง&nbsp; <?php echo $row_studentparent['Parent_tel']; ?></td>
      <td>อาชีพ-ผู้ปกครอง&nbsp; <?php echo $row_studentparent['Parentoccupa']; ?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>รายได้-ผู้ปกครอง&nbsp; <?php echo $row_studentparent['ParentSalary']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</div>
<div class="col-sm-12" style="text-align:center;"><input name="btnprint" id="btnprint" type="button" onClick="window.print()" value="พิมพ์..." class="btn btn-primary"></div>

';

<?php
mysqli_free_result($studentdata);

mysqli_free_result($studentaddress);

mysqli_free_result($studentparent);

mysqli_free_result($entrysemmester);

mysqli_free_result($academicyear);

mysqli_free_result($fatherprefix);

mysqli_free_result($matherprefix);

mysqli_free_result($prefixparent);


$mpdf->WriteHTML($content);

$mpdf->Output();
?>