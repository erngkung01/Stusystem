// Date Thai Format jQuery Plugin
// Developed by DwThai.Com
// Developer : Mr.Sittichai Raksasuk (dwthai@gmail.com)
// 2015
jQuery.fn.dwthaidate = function(t){
	this.each(function(){
		if(t==undefined) t=1;
		var newDate,oldDate;
		var thMonth=new Array('','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
		var shortMonth=new Array('','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.',	'ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
			oldDate=jQuery(this).text();
			var firstTest=parseInt(oldDate[0]);
			if(oldDate.length<4 || isNaN(firstTest)) return;
			var arrDate=oldDate.split(' ');
			var onlyDate=arrDate[0].split('-');
		if(arrDate[1]==undefined) arrDate[1]='';
			onlyDate[0]= (parseInt(onlyDate[0]) +543) + "";
			if(onlyDate[0]<2500) return;
			newDate = onlyDate[2]+'-'+onlyDate[1]+'-'+onlyDate[0]+' '+arrDate[1];
			onlyDate[1]= parseInt(onlyDate[1])
			if(t==2){
				newDate = onlyDate[2]+' '+thMonth[onlyDate[1]]+' '+onlyDate[0]+' '+arrDate[1];
			}else if(t==3){
				newDate = onlyDate[2]+' '+shortMonth[onlyDate[1]]+''+(onlyDate[0].substring(2))+' '+arrDate[1];
			}else if(t==4){	
				newDate = onlyDate[2]+'/'+digit2(onlyDate[1])+'/'+onlyDate[0]+' '+arrDate[1];
			}
			jQuery(this).text(newDate);
		});

};
function digit2(n){
	return n > 9 ? "" + n: "0" + n;
}