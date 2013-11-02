<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

error_reporting(E_ALL);

session_start();

define('FOMIZ_ACCESS', TRUE);

require_once('../config.php');
require_once('check_login.php');
require_once('func.php');

?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>图片上传</title>
<script src="../ui/js/jquery.js" language="javascript"></script>
</HEAD>
<BODY>
<script type="text/javascript">

function Preview(imgFile)
{
document.getElementById("pics").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgFile.value;
}

var right_type=new Array(".jpg",".gif",".png",".jpeg",".bmp")
function checkImgType(fileURL)
{
var right_typeLen=right_type.length;
var imgUrl=fileURL.toLowerCase();
var postfixLen=imgUrl.length;
var len4=imgUrl.substring(postfixLen-4,postfixLen);
var len5=imgUrl.substring(postfixLen-5,postfixLen);
for (i=0;i<right_typeLen;i++)
{
    if((len4==right_type[i])||(len5==right_type[i]))
    {
    return true;
    }
 }
}


function sub(o){
   if(o.file1.value==""){alert("请选择一个图片文件\n");return false;}

   if(checkImgType(o.file1.value)){
    perImg(o.file1.value);
    return true;
   }else{
   alert("您选择的文件格式不正确！");o.file1.focus();
   return false;
   }
}

</script>

<form enctype="multipart/form-data" action="upload.php?action=upload&types=single" onSubmit="return sub(this)" name="uploadform" method="post">
    &nbsp;<input type="file" name= "fmzfile" onChange="if(checkImgType(this.value)){Preview(this);}">
<input type="submit" value="上传图片">

</form>
</BODY>
</HTML>
