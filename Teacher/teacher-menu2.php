<div style="text-align:center;">
<img src="<?php echo $web; ?>/Teacher/images/<?php echo $row_teacher['Teacher_img']; ?>" id="" alt="" height="150" width="150">
</div>
<br>
<div style="text-align:center;">
<a href="<?php echo $web; ?>/Teacher/Uploading.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-primary">แก้ไขรูปภาพ</a>
</div>
<br>
<div style="text-align:center;">
<a href="<?php echo $web; ?>/Teacher/teacherqr/showqr.php?TeacherID=<?php echo $row_teacher['TeacherID']; ?>" class="btn btn-success">QRCode</a>
</div>