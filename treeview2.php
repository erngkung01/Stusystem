
<!doctype html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
ul, #myUL {
  list-style-type: none;
}

#myUL {
  margin: 0;
  padding: 0;
}

.caret {
  cursor: pointer;
  -webkit-user-select: none; /* Safari 3.1+ */
  -moz-user-select: none; /* Firefox 2+ */
  -ms-user-select: none; /* IE 10+ */
  user-select: none;
}

.caret::before {
  content: "\25B6";
  color: black;
  display: inline-block;
  margin-right: 6px;
}

.caret-down::before {
  -ms-transform: rotate(90deg); /* IE 9 */
  -webkit-transform: rotate(90deg); /* Safari */'
  transform: rotate(90deg);  
}

.nested {
  display: none;
}

.active {
  display: block;
}
</style>

</head>
<body>


<div class="container">
<div class="col-sm-12">
<ul id="myUL">
  <li><span class="caret">ระบบบริหารจัดการข้อมูลนักเรียนโรงเรียนอินทารามวิทยา</span>
    <ul class="nested">
     
      <li><span class="caret">ระบบจัดกดารข้อมูล</span>
        <ul class="nested">
          <li>ระบบจัดการข้อมูลนักเรียน</li>
          <li>ระบบจัดการข้อมูลอาจารย์</li>
        
        </ul>
      </li>  
      <li><span class="caret">ระบบจัดการรายละเอียด</span>
        <ul class="nested">
          <li>ระบบจัดการข้อมูลห้องเรียน</li>
          <li>ระบบจัดการปีการศึกษา</li>
          <li>ระบบจัดการข้อมูลตำแหน่ง</li>
        
        </ul>
      </li>  
      <li>ระบบจัดการข้อมูลสมาชิก</li>
      <li>ระบบรายงานข้อมูลนักเรียน</li>
    </ul>
  </li>
</ul>

<script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down");
  });
}
</script>

</div>
</div>
</body>

</html>