<div class="col-sm-3">
<br>
<div class="list-group">
  <a href="<?php echo $web ?>/student/Studentdata/index.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="list-group-item  <?php if($page_active== 1){?>active <?php }?>" >
   จัดการข้อมูลนักเรียน
  </a>
  <a href="<?php echo $web ?>/student/Studentaddress/index.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="list-group-item <?php if($page_active== 2){?>active <?php }?>">จัดการข้อมูลที่อยู่</a>
  
  
  <a href="<?php echo $web ?>/student/studentparent/index.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="list-group-item <?php if($page_active== 3){?>active <?php }?>">จัดการข้อมูลผู้ปกครอง</a>
  
  <a href="<?php echo $web ?>/student/studentaward/index.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="list-group-item <?php if($page_active== 8){?>active <?php }?>">จัดการข้อมูลผลงานรางวัล</a>

  
</div>

</div>