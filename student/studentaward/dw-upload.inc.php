<?php
//Copyright 2017 by DwThai.Com, Inc. All rights reserved.
function dwUpload($file){
	if(empty($file['tmp_name'])){
		return '';
	}else if(@copy($file['tmp_name'],dwtuploadfolder.'/'.$file['name'])){
		@chmod(dwtuploadfolder.'/'.$file['name'],0777);
		return $file['name'];
	}else{
		return false;
	}
}
?>