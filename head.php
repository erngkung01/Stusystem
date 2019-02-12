<?php include("web.php"); ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<? echo $web; ?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<? echo $web; ?>/js/bootstrap.js"></script>
<script type="text/javascript" src="<? echo $web; ?>/dist/js/bootstrap.min.js"></script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
      <a class="navbar-brand" href="<?php echo $web ?>">ระบบข้อมูลนักเรียนโรงเรียนอินทารามวิทยา</a></div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="defaultNavbar1">
      <ul class="nav navbar-nav">
      <li <?php if($page_active==0){?> class="active" <?php }?>><a href="<?php echo $web; ?>">หน้าแรก</a></li>
        <!--เมนูต่างๆ-->
        
       <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222||$_SESSION['MM_UserGroup']==333){ ?>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">ระบบจัดการข้อมูล
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="<?php echo $web;  ?>/student/index.php">ระบบจัดการข้อมูลนักเรียน</a></li>
          <li><a href="<?php echo $web; ?>/Teacher/index.php">ระบบจัดการข้อมูลอาจารย์</a></li>
          
        </ul>
      </li>
      <?php } ?>
        
        <?php if($_SESSION['MM_UserGroup']==111||$_SESSION['MM_UserGroup']==222){  ?>
         <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">ระบบจัดการรายละเอียด
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
        <li ><a href="<?php echo $web; ?>/Group/index.php">ระบบจัดการข้อมูลห้องเรียน</a></li>
        <li ><a href="<?php echo $web; ?>/AcademicYear/index.php">ระบบจัดการปีการศึกษา</a></li>
         <li><a href="<?php echo $web; ?>/Position/index.php">ระบบจัดการข้อมูลตำแหน่ง</a></li>   
         </ul>
      </li>
        
        
		<?php } ?>
        <!--จบเมนูต่างๆ-->
        
        <?php if($_SESSION['MM_UserGroup']==111){ ?>
        
     	<li><a href="<?php echo $web;  ?>/user/index.php">ระบบจัดการข้อมูลสมาชิก</a></li>

       <?php  } ?>
        	<li><a href="<?php echo $web;  ?>/chart/index.php">ระบบรายงานข้อมูลนักเรียน</a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo  $_SESSION['MM_Username']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<? echo $web; ?>/user/update-mem.php">แก้ไขข้อมูลส่วนตัว</a></li>
            <li><a href="<? echo $web; ?>/logout.php">ออกจากระบบ</a></li>
            
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container-fluid -->
</nav>

<script src="<?php echo $web;?>/js/bootstrap.js"></script>
<script src="<?php echo $web;?>/js/jquery-1.11.2.min.js"></script>