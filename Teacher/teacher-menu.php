<div class="col-sm-3">
<br>
<div class="list-group">
  <a href="<?php echo $web ?>/Teacher/data.php?TeacherID=<?php echo  $row_teacher['TeacherID']; ?>" class="list-group-item  <?php if($page_active== 9){?>active <?php }?>" >
   จัดการข้อมูลอาจารย์
  </a>
  <a href="<?php echo $web ?>/Teacher/teacheraddress/index.php?TeacherID=<?php echo  $row_teacher['TeacherID'];  ?>" class="list-group-item <?php if($page_active== 11){?>active <?php }?>">จัดการข้อมูลที่อยู่อาจารย์</a>
  <a href="<?php echo $web ?>/Teacher/teacheraward/index.php?TeacherID=<?php echo  $row_teacher['TeacherID'];  ?>" class="list-group-item <?php if($page_active== 10){?>active <?php }?>">จัดการข้อมูลผลงาน/รางวัล</a>
  <a href="<?php echo $web ?>/Teacher/teacherduty/teacherdutyyear/index.php?TeacherID=<?php echo  $row_teacher['TeacherID'];  ?>" class="list-group-item <?php if($page_active== 12){?>active <?php }?>">จัดการข้อมูลหน้าที่</a>
  
  

  
 

  
</div>

</div>