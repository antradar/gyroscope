<?php
set_time_limit(0);

//include 'libresize.php'; //available with commercial license

function embeduserprofileuploader(){
	header('Cache-Control: no-store');
	
	global $db;
	$user=userinfo();
	$userid=intval($user['userid']);

	$query="select darkmode from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$dark=$myrow['darkmode'];

	$dmaxsize=20; //20 MB
	$maxsize=1024*1024*$dmaxsize;
	
	global $tinypngapi; //comment this out to disable TinyPNG
	
	global $codepage;
	
	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';	

	$path='../../protected/userpics/'.$vendorhead;
		
	$msg='';
		
	
	if (isset($_FILES['file'])&&$_FILES['file']&&$_FILES['file']['name']){
		checkgskey('embeduserprofileuploader_'.$userid);
		//echo '<pre>';print_r($_FILES);echo '</pre>'; return;
			
		$file=$_FILES['file'];
		$realfns=$file['tmp_name'];
		$filesizes=$file['size'];
		$filetypes=$file['type'];
		
		$ofn=noapos($file['name']);
		
			$realfn=$realfns;//[$idx];
			$filesize=$filesizes;//[$idx];
			$filetype=$filetypes;//[$idx];
			$dst=$path.$userid.'.png';
			$error=$file['error'];

			$c=file_get_contents($realfn);

			$img=@imagecreatefromstring($c);
			if (!$img){
				$msg='Invalid image format. JPG and PNG only.';
				if ($error==2) $msg='File too large';

			} else {
				$w=imagesx($img);
				$h=imagesy($img);
				if ($w>3500||$h>3000) {//increase this if you have more RAM
					$msg='Image too large. Upload a smaller one.';
					imagedestroy($img);
				} else {
				
					if ($w==270&&$h==270&&$tinypngapi==''){//direct file upload
						$f=fopen($dst,'wb');
						fwrite($f,$c);
						fclose($f);
						@chmod($dst,0777);
					} else{
						
						if (!is_callable('image_cropfit')) apperror("libresize.php is missing");
						
						if ($tinypngapi!=''&&$w>=270&&$h>=270){
							$crops=tinypng_crop($c,array('main'=>array('width'=>270,'height'=>270)),$tinypngapi);
							
							$f=fopen($dst,'wb'); fwrite($f,$crops['main']); fclose($f);
							
						} else {				
							//$img=image_setorientation($img,'landscape');
							$thumb=image_cropfit($img,270,270);
							imagepng($thumb,$dst);
							@chmod($dst,0777);
							imagedestroy($thumb);
						}
						
						imagedestroy($img);
					}
			
					$query="update ".TABLENAME_USERS." set haspic=1,imgv=imgv+1 where userid=?"; //,ampw=?,amph=?
					sql_prep($query,$db,array($userid)); //$w,$h
			
						
					logaction("uploaded $ofn to user #$userid",array('userid'=>$userid,'file'=>$ofn));
				}
			}
								
			
					
		
		if (!$_GET['diag']){
?>
<script>parent.showuserprofile(<?php echo $userid;?>,"<?php echo $msg;?>");</script>
<?php			
		}
		
	}
	
	if (isset($_GET['diag'])&&$_GET['diag']) {
		echo $msg;
		return;
	}
	
?>
<html>
<body style="padding:0;margin:0;">
<style>
<?php include 'uploaderstyle.php';?>
</style>
<form id="uploader" style="padding:0;margin:0;" method="POST" enctype="multipart/form-data">
<div id="dropzone" style="display:none;margin:20px 0;padding:10px;border:dashed 2px #dedede;text-align:center;font-size:20px;color:#dedede;">
	<span id="dropzonetext">Drag and drop file here</span>
	<span id="dropzoneprogress"></span>
</div>
<input type="hidden" name="userid" value="<?php echo $userid;?>">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxsize;?>">
<input type="hidden" name="X-GSREQ-KEY" value="<?php emitgskey('embeduserprofileuploader_'.$userid);?>">
<input type="file" name="file" id="file" onchange="upload(this);return false;">
</form>
<div id="cancel" style="display:none;font-size:12px;padding:5px 0;"><a href=# onclick="cancelupload();return false;"><u>cancel</u></a></div>
<div id="debug" style="font-size:11px;"></div>
<script src="nano.js"></script>
<script>
function cancelupload(){
	var btn=gid('file');
	if (!btn.rq) return;
	btn.rq.abort();	
}

function upload(d,dfiles){
	var file=gid('file');
	var form=gid('uploader');
	var rq=xmlHTTPRequestObject();

	var html5=(self.FormData&&rq.upload&&file.files)?1:0;
	if (!html5){
		d.parentNode.submit();
		return;
	}

	var fd=new FormData();

	var binfile=file.files[0];
	if (dfiles) binfile=dfiles[0];
	
	var fsize=0;
	if (binfile&&binfile.size) fsize=binfile.size;
	if (fsize><?php echo $maxsize;?>){
		alert('File too large. Upload a smaller file');
		return;	
	}

	fd.append('file',binfile);
	fd.append('X-GSREQ-KEY','<?php emitgskey('embeduserprofileuploader_'.$userid);?>');
	
	fd.append('userid',<?php echo $userid;?>);
	rq.open('POST','<?php echo $codepage;?>?cmd=embeduserprofileuploader&userid=<?php echo $userid;?>&diag=1',true);

	rq.upload.onprogress=function(e){
		gid('cancel').style.display='block';
		if (e.lengthComputable) {
			gid('debug').innerHTML=Math.floor(e.loaded*100/e.total)+'%';
			if (Math.floor(e.loaded*100/e.total)==100) gid('debug').innerHTML='Processing...';
			gid('dropzoneprogress').innerHTML=gid('debug').innerHTML;
		} else gid('debug').innerHTML='uploading...';
	};
	rq.onload=function(e){
		gid('debug').innerHTML='uploaded';
		gid('cancel').style.display='none';
		if (e.target.responseText!=null&&e.target.responseText!='') {
			alert(e.target.responseText);
			return;
		}
		parent.showuserprofile(<?php echo $userid;?>,'');
	}

	rq.onabort=function(e){
		gid('cancel').style.display='none';
		gid('debug').innerHTML='canceled';
		gid('dropzonetext').innerHTML='Upload canceled';
		gid('dropzoneprogress').innerHTML='';	
	}
	
	d.rq=rq;
	
	rq.send(fd);	
}

if (self.FormData&&'draggable' in document.createElement('span')){
		gid('dropzone').style.display='block';
		gid('dropzone').ondragover=function(){this.style.borderColor='#848cf7';this.style.color='#848cf7';return false;}
		gid('dropzone').ondragleave=function(){this.style.borderColor='#dedede';this.style.color='#dedede';return false;}
		gid('dropzone').ondragend=function(){this.style.borderColor='#dedede';this.style.color='#dedede';return false;}
		gid('dropzone').ondrop=function(e){
			var files=e.dataTransfer.files;
			if (!files||files.length==0){
				gid('dropzonetext').innerHTML='invalid file';
				gid('dropzoneprogress').innerHTML='';
				return false;
			}			
			if (files.length>1){
				gid('dropzonetext').innerHTML='multiple upload not supported';
				gid('dropzoneprogress').innerHTML='';
				return false;	
			}
			var fns=[];
			for (var i=0;i<files.length;i++) fns.push(files[i].name);
			gid('dropzonetext').innerHTML=files[0].name;
			gid('dropzoneprogress').innerHTML='';
			
			upload(gid('file'),files);
			return false;	
		}
}

</script>

</body>
</html>
<?php
		
}
