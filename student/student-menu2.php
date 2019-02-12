 <div style="text-align:center;">
      <img src="<?php echo $web ?>/student/Studentdata/images/<?php echo $row_studentdata['student_img']; ?>" id="" alt="" height="150" width="150">
    </div>
    <br>
    <div style="text-align:center;">
      <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){ ?>
      <a href="<?php echo $web ?>/student/Studentdata/Uploadimg.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-primary">แก้ไขรูปภาพ</a>
      
      <?php } ?>
    </div>
    <br>
    <div style="text-align:center;">
<a href="<?php echo $web; ?>/student/studentqr/showqr.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-success">QRCode</a>
<br>
<br>
<a href="<?php echo $web; ?>/student/print.php?studentID=<?php echo $row_studentdata['studentID']; ?>" class="btn btn-primary">พิมพ์ข้อมูล</a>
</div>