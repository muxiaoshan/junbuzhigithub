<?php

/**
 * 类库：图像处理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package handler
 * @name image.han.php
 * @version 1.0
 */

class ImageHandler
{
	
	public function Info( $file )
	{
		$imageInfo = @getimagesize($file);
		if ( $imageInfo !== false )
		{
			$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
			$imageSize = filesize($file);
			$info = array( 
				"width" => $imageInfo[0], "height" => $imageInfo[1], "type" => $imageType, "size" => $imageSize, "mime" => $imageInfo['mime'] 
			);
			return $info;
		}
		else
		{
			return false;
		}
	}

	
	public function Thumb( $image, $thumb, $ext = '', $width = 200, $height = 121, $interlace = true )
	{
		$info = $this->Info($image);
		if ( $info !== false )
		{
			$srcWidth = $info['width'];
			$srcHeight = $info['height'];
			$ext = empty($ext) ? $info['type'] : $ext;
			$ext = strtolower($ext);
			$interlace = $interlace ? 1 : 0;
			unset($info);
						$createFun = 'ImageCreateFrom' . ($ext == 'jpg' ? 'jpeg' : $ext);
			if (!function_exists($createFun)) return $image;
			$srcImg = $createFun($image);
						if ( $ext != 'gif' && function_exists('imagecreatetruecolor') )
				$thumbImg = imagecreatetruecolor($width, $height);
			else $thumbImg = imagecreate($width, $height);
						$bgcolor = 'FFFFFF';
			$red = $green = $blue = 0;
			sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
			$clr = imagecolorallocate($thumbImg, $red, $green, $blue);
			imagefilledrectangle($thumbImg, 0, 0, $width, $height, $clr);
			$scale_org = $srcWidth / $srcHeight;
			if ( $srcWidth / $width > $srcHeight / $height )
			{
				$lessen_width = $width;
				$lessen_height = $width / $scale_org;
			}
			else
			{
				$lessen_width = $height * $scale_org;
				$lessen_height = $height;
			}
			$dst_x = ($width - $lessen_width) / 2;
			$dst_y = ($height - $lessen_height) / 2;
			if ( function_exists("ImageCopyResampled") )
				imagecopyresampled($thumbImg, $srcImg, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $srcWidth, $srcHeight);
			else imagecopyresized($thumbImg, $srcImg, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $srcWidth, $srcHeight);
			if ( 'gif' == $ext || 'png' == $ext )
			{
				$background_color = imagecolorallocate($thumbImg, 0, 255, 0);
				imagecolortransparent($thumbImg, $background_color);
			}
			if ( 'jpg' == $ext || 'jpeg' == $ext ) imageinterlace($thumbImg, $interlace);
			$imageFun = 'image' . ($ext == 'jpg' ? 'jpeg' : $ext);
			$this->InitPath($thumb);
			$imageFun($thumbImg, $thumb);
			imagedestroy($thumbImg);
			imagedestroy($srcImg);
			return $thumb;
		}
		return false;
	}

	
	static function buildString( $filename, $string, $rgb = array(), $size = '', $font = '', $type = 'png', $disturb = 1, $border = true )
	{
		if ( is_string($size) ) $size = explode(',', $size);
		$width = $size[0];
		$height = $size[1];
		if ( is_string($font) ) $font = explode(',', $font);
		$fontface = $font[0];
		$fontsize = $font[1];
		$length = strlen($string);
		$width = ($length * 9 + 10) > $width ? $length * 9 + 10 : $width;
		$height = 22;
		if ( $type != 'gif' && function_exists('imagecreatetruecolor') )
		{
			$im = @imagecreatetruecolor($width, $height);
		}
		else
		{
			$im = @imagecreate($width, $height);
		}
		if ( empty($rgb) )
		{
			$color = imagecolorallocate($im, 102, 104, 104);
		}
		else
		{
			$color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
		}
		$backColor = imagecolorallocate($im, 255, 255, 255); 		$borderColor = imagecolorallocate($im, 100, 100, 100); 		$pointColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); 		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
		@imagestring($im, 5, 5, 3, $string, $color);
		if ( ! empty($disturb) )
		{
						if ( $disturb == 1 || $disturb == 3 )
			{
				for ( $i = 0; $i < 25; $i ++ )
				{
					imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointColor);
				}
			}
			elseif ( $disturb == 2 || $disturb == 3 )
			{
				for ( $i = 0; $i < 10; $i ++ )
				{
					imagearc($im, mt_rand(- 10, $width), mt_rand(- 10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $pointColor);
				}
			}
		}
		$this->output($im, $type, $filename);
	}

	
	static function buildImageVerify( $length = 4, $mode = 1, $type = 'gif', $width = 48, $height = 22, $verifyName = 'verify' )
	{
		require_once APP_ROOT_PATH . "system/utils/es_string.php";
		$randval = $this->rand_string($length, $mode);
		$_SESSION[$verifyName] = md5($randval);
		$width = ($length * 10 + 10) > $width ? $length * 10 + 10 : $width;
		if ( $type != 'gif' && function_exists('imagecreatetruecolor') )
		{
			$im = @imagecreatetruecolor($width, $height);
		}
		else
		{
			$im = @imagecreate($width, $height);
		}
		$r = Array( 
			225, 255, 255, 223 
		);
		$g = Array( 
			225, 236, 237, 255 
		);
		$b = Array( 
			225, 236, 166, 125 
		);
		$key = mt_rand(0, 3);
		$backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]); 		$borderColor = imagecolorallocate($im, 100, 100, 100); 		$pointColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); 		@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		@imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
		$stringColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
				for ( $i = 0; $i < 10; $i ++ )
		{
			$fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($im, mt_rand(- 10, $width), mt_rand(- 10, $height), mt_rand(30, 300), mt_rand(20, 200), 55, 44, $fontcolor);
		}
		for ( $i = 0; $i < 25; $i ++ )
		{
			$fontcolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointColor);
		}
		for ( $i = 0; $i < $length; $i ++ )
		{
			imagestring($im, 5, $i * 10 + 5, mt_rand(1, 8), $randval{$i}, $stringColor);
		}
				$this->output($im, $type);
	}

   
	
	public function Conv2ASC( $image, $string = '', $type = '' )
	{
		$info = $this->Info($image);
		if ( $info !== false )
		{
			$type = empty($type) ? $info['type'] : $type;
			unset($info);
						$createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
			$im = $createFun($image);
			$dx = imagesx($im);
			$dy = imagesy($im);
			$i = 0;
			$out = '<span style="padding:0px;margin:0;line-height:100%;font-size:1px;">';
			set_time_limit(0);
			for ( $y = 0; $y < $dy; $y ++ )
			{
				for ( $x = 0; $x < $dx; $x ++ )
				{
					$col = imagecolorat($im, $x, $y);
					$rgb = imagecolorsforindex($im, $col);
					$str = empty($string) ? '*' : $string[$i ++];
					$out .= sprintf('<span style="margin:0px;color:#%02x%02x%02x">' . $str . '</span>', $rgb['red'], $rgb['green'], $rgb['blue']);
				}
				$out .= "<br>\n";
			}
			$out .= '</span>';
			imagedestroy($im);
			return $out;
		}
		return false;
	}

	
	static function showAdvVerify( $type = 'png', $width = 180, $height = 40, $verifyName = 'verifyCode' )
	{
		$rand = range('a', 'z');
		shuffle($rand);
		$verifyCode = array_slice($rand, 0, 10);
		$letter = implode(" ", $verifyCode);
		$_SESSION[$verifyName] = $verifyCode;
		$im = imagecreate($width, $height);
		$r = array( 
			225, 255, 255, 223 
		);
		$g = array( 
			225, 236, 237, 255 
		);
		$b = array( 
			225, 236, 166, 125 
		);
		$key = mt_rand(0, 3);
		$backColor = imagecolorallocate($im, $r[$key], $g[$key], $b[$key]);
		$borderColor = imagecolorallocate($im, 100, 100, 100); 		imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
		imagerectangle($im, 0, 0, $width - 1, $height - 1, $borderColor);
		$numberColor = imagecolorallocate($im, 255, rand(0, 100), rand(0, 100));
		$stringColor = imagecolorallocate($im, rand(0, 100), rand(0, 100), 255);
				
		imagestring($im, 5, 5, 1, "0 1 2 3 4 5 6 7 8 9", $numberColor);
		imagestring($im, 5, 5, 20, $letter, $stringColor);
		$this->output($im, $type);
	}

	
	static function UPCA( $code, $type = 'png', $lw = 2, $hi = 100 )
	{
		static $Lencode = array( 
			'0001101', '0011001', '0010011', '0111101', '0100011', '0110001', '0101111', '0111011', '0110111', '0001011' 
		);
		static $Rencode = array( 
			'1110010', '1100110', '1101100', '1000010', '1011100', '1001110', '1010000', '1000100', '1001000', '1110100' 
		);
		$ends = '101';
		$center = '01010';
		
		if ( strlen($code) != 11 )
		{
			die("UPC-A Must be 11 digits.");
		}
		
		$ncode = '0' . $code;
		$even = 0;
		$odd = 0;
		for ( $x = 0; $x < 12; $x ++ )
		{
			if ( $x % 2 )
			{
				$odd += $ncode[$x];
			}
			else
			{
				$even += $ncode[$x];
			}
		}
		$code .= (10 - (($odd * 3 + $even) % 10)) % 10;
		
		$bars = $ends;
		$bars .= $Lencode[$code[0]];
		for ( $x = 1; $x < 6; $x ++ )
		{
			$bars .= $Lencode[$code[$x]];
		}
		$bars .= $center;
		for ( $x = 6; $x < 12; $x ++ )
		{
			$bars .= $Rencode[$code[$x]];
		}
		$bars .= $ends;
		
		if ( $type != 'gif' && function_exists('imagecreatetruecolor') )
		{
			$im = imagecreatetruecolor($lw * 95 + 30, $hi + 30);
		}
		else
		{
			$im = imagecreate($lw * 95 + 30, $hi + 30);
		}
		$fg = ImageColorAllocate($im, 0, 0, 0);
		$bg = ImageColorAllocate($im, 255, 255, 255);
		ImageFilledRectangle($im, 0, 0, $lw * 95 + 30, $hi + 30, $bg);
		$shift = 10;
		for ( $x = 0; $x < strlen($bars); $x ++ )
		{
			if ( ($x < 10) || ($x >= 45 && $x < 50) || ($x >= 85) )
			{
				$sh = 10;
			}
			else
			{
				$sh = 0;
			}
			if ( $bars[$x] == '1' )
			{
				$color = $fg;
			}
			else
			{
				$color = $bg;
			}
			ImageFilledRectangle($im, ($x * $lw) + 15, 5, ($x + 1) * $lw + 14, $hi + 5 + $sh, $color);
		}
		
		ImageString($im, 4, 5, $hi - 5, $code[0], $fg);
		for ( $x = 0; $x < 5; $x ++ )
		{
			ImageString($im, 5, $lw * (13 + $x * 6) + 15, $hi + 5, $code[$x + 1], $fg);
			ImageString($im, 5, $lw * (53 + $x * 6) + 15, $hi + 5, $code[$x + 6], $fg);
		}
		ImageString($im, 4, $lw * 95 + 17, $hi - 5, $code[11], $fg);
		
		$this->output($im, $type);
	}

	static function output( $im, $type = 'png', $filename = '' )
	{
		header("Content-type: image/" . $type);
		$ImageFun = 'image' . $type;
		if ( empty($filename) )
		{
			$ImageFun($im);
		}
		else
		{
			$ImageFun($im, $filename);
		}
		imagedestroy($im);
	}

	
	public function water( $source, $water, $savename = null, $alpha = 80, $position = 0 )
	{
				if ( ! file_exists($source) || ! file_exists($water) ) return false;
				$sInfo = self::Info($source);
		$wInfo = self::Info($water);
				if ( $sInfo["width"] < $wInfo["width"] || $sInfo['height'] < $wInfo['height'] ) return false;
				$sCreateFun = "imagecreatefrom" . $sInfo['type'];
		$sImage = $sCreateFun($source);
		$wCreateFun = "imagecreatefrom" . $wInfo['type'];
		$wImage = $wCreateFun($water);
				imagealphablending($wImage, true);
		switch ( $position )
		{
			case 0 :
				break;
						case 1 :
				$posY = 0;
				$posX = 0;
								imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);
				break;
						case 2 :
				$posY = 0;
				$posX = $sInfo["width"] - $wInfo["width"];
								imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);
				break;
						case 3 :
				$posY = $sInfo["height"] - $wInfo["height"];
				$posX = 0;
								imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);
				break;
						case 4 :
				$posY = $sInfo["height"] - $wInfo["height"];
				$posX = $sInfo["width"] - $wInfo["width"];
								imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);
				break;
						case 5 :
				$posY = $sInfo["height"] / 2 - $wInfo["height"] / 2;
				$posX = $sInfo["width"] / 2 - $wInfo["width"] / 2;
								imagecopymerge($sImage, $wImage, $posX, $posY, 0, 0, $wInfo['width'], $wInfo['height'], $alpha);
				break;
		}
				$ImageFun = 'Image' . $sInfo['type'];
				if ( ! $savename )
		{
			$savename = $source;
			@unlink($source);
		}
				$ImageFun($sImage, $savename);
		imagedestroy($sImage);
	}
	
	private function InitPath($path)
	{
		$all = explode('/', $path);
		$path = implode('/', array_slice($all, 0, count($all)-1));
		if ( !is_dir($path) )
		{
			$list = explode('/', $path);
			$path = '';
			foreach ($list as $i => $dir)
			{
				if ($dir == '') continue;
				$path .= $dir.'/';
				if ( !is_dir($path) )
				{
					@mkdir($path, 0777);
				}
			}
		}
	}
}
if ( ! function_exists('image_type_to_extension') )
{

	function image_type_to_extension( $imagetype )
	{
		if ( empty($imagetype) ) return false;
		switch ( $imagetype )
		{
			case IMAGETYPE_GIF :
				return '.gif';
			case IMAGETYPE_JPEG :
				return '.jpeg';
			case IMAGETYPE_PNG :
				return '.png';
			case IMAGETYPE_SWF :
				return '.swf';
			case IMAGETYPE_PSD :
				return '.psd';
			case IMAGETYPE_BMP :
				return '.bmp';
			case IMAGETYPE_TIFF_II :
				return '.tiff';
			case IMAGETYPE_TIFF_MM :
				return '.tiff';
			case IMAGETYPE_JPC :
				return '.jpc';
			case IMAGETYPE_JP2 :
				return '.jp2';
			case IMAGETYPE_JPX :
				return '.jpf';
			case IMAGETYPE_JB2 :
				return '.jb2';
			case IMAGETYPE_SWC :
				return '.swc';
			case IMAGETYPE_IFF :
				return '.aiff';
			case IMAGETYPE_WBMP :
				return '.wbmp';
			case IMAGETYPE_XBM :
				return '.xbm';
			default :
				return false;
		}
	}
}
?>