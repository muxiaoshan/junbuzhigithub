<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename error_404.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 

header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <title>没有找到您要访问的页面</title>
 <STYLE>BODY {
	FONT-SIZE: 14px; FONT-FAMILY: arial,sans-serif
}

H1 {
	FONT-SIZE: 22px
}
UL {
	MARGIN: 1em
}
LI {
	LINE-HEIGHT: 2em; FONT-FAMILY: 宋体
}
A {
	COLOR: #00f
}
</STYLE>
</head>
 <body>
<BLOCKQUOTE>
  <H1>没有找到您要访问的页面</H1>The requested URL was not found on this server. 
  <OL>
    <LI>出现这个页面，可能是你输入的网址不正确；
	<LI>也可能是站长设置了服务器不支持的URL静态化模式；


	</LI></OL>
  <P></P></BLOCKQUOTE> </body>
</html>
<?php exit;?>