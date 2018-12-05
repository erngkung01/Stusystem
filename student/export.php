<?php include ("../Connections/stusystem.php");?>
<?php  
//export.php  

$output = '';
if(isset($_POST["export"]))
{
 $query = "SELECT a.*,b.*,c.*,d.*,e.*,f.*,g.*,h.*,i.*,j.* FROM tbl_student as a left join tbl_prefix as b on a.PrefixCode=b.PrefixCode left join tbl_gender as c on  a.GenderCode=c.GenderCode left join tbl_blger as d on  a.blgerid=d.blgerid  left join tbl_religion as e on  a.Religionid=e.Religionid left join tbl_group as f on a.groupid=f.groupid  left join tbl_studentstatus as g on  a.statusid=g.statusid left join tbl_classlevel as h on  a.GradeLevel=h.classlevelid left join tbl_academicyears as i on  a.AcademicYears=i.AcademicYearsID left join tbl_semester as j on  a.Semester=j.SemesterID WHERE (a.PrefixCode=b.PrefixCode or b.PrefixCode is null ) and  (a.GenderCode=c.GenderCode or c.GenderCode is null ) and  (a.blgerid=d.blgerid or d.blgerid is null ) and ( a.Religionid=e.Religionid or e.Religionid is null ) and  (a.groupid=f.groupid or f.groupid is null ) and ( a.statusid=g.statusid or g.statusid is null  ) and ( a.GradeLevel=h.classlevelid or h.classlevelid is null ) and ( a.AcademicYears=i.AcademicYearsID or i.AcademicYearsID is null )  and ( a.Semester=j.SemesterID or j.SemesterID is null  )";
 $result = mysqli_query($stusystem, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>รหัสนักเรียน</th>  
                         <th>รหัสประชาชน</th>  
                         <th>คำนำหน้าชื่อ</th>  
       <th>ชื่อ</th>
       <th>นามสกุล</th>
	   <th>เพศ</th>
	   <th>หมู่เลือด</th>
	   <th>วัน/เดือน/ปีเกิด</th>
	   <th>ชื่อเล่น</th>
	   <th>ศาสนา</th>
	   <th>ปีการศึกษาที่เข้าเรียน</th>
	   <th>ปีการศึกษา</th>
	   <th>ระดับชั้น</th>
	   <th>ห้องเรียน</th>
	   <th>ภาคเรียน</th>
	   <th>สถาณะนักเรียน</th>
	   <th>เบอร์โทรศัพท์นักเรียน</th>
	   <th>บ้านเลขที่</th>
	   <th>หมู่</th>
	   <th>ถนน</th>
	   <th>ตำบล</th>
	   <th>อำเภอ</th>
	   <th>จังหวัด</th>
	   <th>รหัสไปรษณีย์</th>
	   <th>คำนำหน้าชื่อ-บิดา</th>
	   <th>ชื่อ-บิดา</th>
	   <th>นามสกุล-บิดา</th>
	   <th>เบอร์โทรศัพท์-บิดา</th>
	   <th>อาชีพ-บิดา</th>
	   <th>รายได้-บิดา</th>
	   <th>คำนำหน้าชื่อ-มารดา</th>
	   <th>ชื่อ-มารดา</th>
	   <th>นามสกุล-มารดา</th>
	   <th>เบอร์โทรศัพท์-มารดา</th>
	   <th>อาฃีพ-มารดา</th>
	   <th>รายได้-มารดา</th>
	   <th>คำนำหน้าชื่อ-ผู้ปกครอง</th>
	   <th>ชื่อ-ผู้ปกครอง</th>
	   <th>นามสกุล-ผู้ปกครอง</th>
	   <th>เบอร์โทรศัพท์-ผู้ปกครอง</th>
	   <th>อาฃีพ-ผู้ปกครอง</th>
	   <th>รายได้-ผู้ปกครอง</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
	  //ข้อมูลที่อยู่นักเรียน
	  $studentID= $row["studentID"];
	  $studentaddressquery = "SELECT a.*,b.*,c.*,d.* FROM tbl_address as a left join  province as b on a.PROVINCE_ID=b.PROVINCE_ID left join  district as c on a.DISTRICT_ID=c.DISTRICT_ID left join  amphur as d on a.AMPHUR_ID=d.AMPHUR_ID WHERE a.studentID = ".$studentID." and  (a.PROVINCE_ID=b.PROVINCE_ID or b.PROVINCE_ID  is null  ) and  (a.DISTRICT_ID=c.DISTRICT_ID or c.DISTRICT_ID is null  ) and  (a.AMPHUR_ID=d.AMPHUR_ID or d.AMPHUR_ID is null  )";
	  $studentaddressresult = mysqli_query($stusystem,$studentaddressquery);
	  $studentaddressrow = mysqli_fetch_assoc($studentaddressresult);
	  //ข้อมูลผู้ปกครองนักเรียน
	  $studentparentquery = "SELECT * FROM tbl_parent WHERE studentID = ".$studentID."";
	  $studentparentresult = mysqli_query($stusystem,$studentparentquery);
	  $studentparentrow = mysqli_fetch_assoc($studentparentresult);
	  //คำนำหน้าชื่อ บิดา
	  $fatherprefix = $studentparentrow["FatherPrefixCode"];
	  $prefixfatherquery = "SELECT * FROM tbl_prefix WHERE PrefixCode=".$fatherprefix."";
	  $prefixfatherresult = mysqli_query($stusystem,$prefixfatherquery);
	  $prefixfatherrow = mysqli_fetch_assoc($prefixfatherresult);
	  //คำนำหน้าชื่อ มารดา
	  $matherprefix = $studentparentrow["MatherPrefixCode"];
	  $prefixmatherquery = "SELECT * FROM tbl_prefix WHERE PrefixCode=".$matherprefix."";
	  $prefixmatherresult = mysqli_query($stusystem,$prefixmatherquery);
	  $prefixmatherrow = mysqli_fetch_assoc($prefixmatherresult);
	  //คำนำหน้าชื่อ ผู้ปกครอง
	  $parentprefix = $studentparentrow["ParentPrefixCode"];
	  $prefixparentquery = "SELECT * FROM tbl_prefix WHERE PrefixCode=".$parentprefix."";
	  $prefixparentresult = mysqli_query($stusystem,$prefixparentquery);
	  $prefixparentrow = mysqli_fetch_assoc($prefixparentresult);
	  //ปีการศึกษาที่เข้าเรียน
	  $entryacademicyear = $row["EntryAcademicYear"];
	  $entryacademicyearquery = "SELECT * FROM tbl_academicyears WHERE AcademicYearsID=".$entryacademicyear."";
	  $entryacademicyearresult = mysqli_query($stusystem,$entryacademicyearquery);
	  $entryacademicyearrow = mysqli_fetch_assoc($entryacademicyearresult);
	  
   $output .= '
    <tr>  
                         <td>'.$row["studentID"].'</td>  
                         <td>'.$row["PersonID"].'</td>  
                         <td>'.$row["PrefixName"].'</td>  
       <td>'.$row["student_name"].'</td>  
       <td>'.$row["student_surname"].'</td>
	   <td>'.$row["GenderName"].'</td>
	   <td>'.$row["blgerName"].'</td>
	   <td>'.$row["student_Birthdate"].'</td>
	   <td>'.$row["Student_Nickname"].'</td>
	   <td>'.$row["Religionname"].'</td>
	   <td>'.$entryacademicyearrow["AcademicYears"].'</td>
	   <td>'.$row["AcademicYears"].'</td>
	   <td>'.$row["classlevelname"].'</td>
	   <td>'.$row["group_name"].'</td>
	   <td>'.$row["Semester"].'</td>
	   <td>'.$row["Statusname"].'</td>
	   <td>'.$row["stu_tel"].'</td>
	   <td>'.$studentaddressrow["Homeid"].'</td>
	   <td>'.$studentaddressrow["moo"].'</td>
	   <td>'.$studentaddressrow["street"].'</td>
	   <td>'.$studentaddressrow["DISTRICT_NAME"].'</td>
	   <td>'.$studentaddressrow["AMPHUR_NAME"].'</td>
	   <td>'.$studentaddressrow["PROVINCE_NAME"].'</td>
	   <td>'.$studentaddressrow["ZipCode"].'</td>
	   <td>'.$prefixfatherrow["PrefixName"].'</td>
	   <td>'.$studentparentrow["FatherName"].'</td>
	   <td>'.$studentparentrow["FatherSurName"].'</td>
	   <td>'.$studentparentrow["Father_tel"].'</td>
	   <td>'.$studentparentrow["Fatheroccupa"].'</td>
	   <td>'.$studentparentrow["FatherSalary"].'</td>
	   <td>'.$prefixmatherrow["PrefixName"].'</td>
	   <td>'.$studentparentrow["Mathername"].'</td>
	   <td>'.$studentparentrow["Mathersurname"].'</td>
	   <td>'.$studentparentrow["Mather_tel"].'</td>
	   <td>'.$studentparentrow["Matheroccupa"].'</td>
	   <td>'.$studentparentrow["MatherSalary"].'</td>
	   <td>'.$prefixparentrow["PrefixName"].'</td>
	   <td>'.$studentparentrow["ParentName"].'</td>
	   <td>'.$studentparentrow["ParentSurname"].'</td>
	   <td>'.$studentparentrow["Parent_tel"].'</td>
	   <td>'.$studentparentrow["Parentoccupa"].'</td>
	   <td>'.$studentparentrow["ParentSalary"].'</td>
	   
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=download.xls');
  echo $output;
 }
}
?>