<?php defined('SYSPATH') OR die('No direct script access.');

class Image_vmsImage extends Image_GD {

	CONST IAMGE_PROJECT_VMS = 'video';
	CONST S3_URL = "http://ps3.v.iask.com/";
	
	/**
	 * 保存通过表单上传的图片文件并裁剪尺寸
	 * @param string $image
	 * @param string $path
	 * @param number $width
	 * @param number $height
	 * @param unknown $master
	 * @return boolean|string
	 */
	public static function uploadImage($image = NULL, $path = NULL, $fileName = NULL, $width = 0, $height = 0, $master = Image::NONE) {
		
		// check is uploaded file a valid image file
		if( !Upload::not_empty($image) OR !Upload::valid($image) OR !Upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))) {
			return FALSE;
		}
		// save image to 'uploads' foler in docroot
		if($file = Upload::save($image, NULL, $path)) {
			$extension = Image_GD::getImageType($image);
			// randomly generate 20 alphanumeric characters as file name
			if(!$fileName) {
				$fileName = strtolower(Text::random('alnum', 20)).'.'.$extension;
			}
			if($width && $height) {
				Image::factory($file)
					->resize($width, $height, $master)
					->save($path.$fileName);
			} else {
				Image::factory($file)
					->save($path.$fileName);
			}
			// remove image file with old name
			unlink($file);
			return $path.$fileName;
		}
		return NULL;
	}
	
	/**
	 * 生成s3图片链接地址名
	 * @param int $videoId
	 */
	public static function getS3Path($videoId = 0) {
		if(!$videoId) {
			return FALSE;
		}
		//生成图片地址规则
		$videoIdFormatString = number_format((int)$videoId);
		$directorys = explode(',', $videoIdFormatString);
		if(strlen($directorys[0]) < 3) {
			$directorys[0] = str_pad($directorys[0], 3, '0', STR_PAD_LEFT);
		}
		$s3ImagePath = implode('/', $directorys);
		return  $s3ImagePath;
 	}
 	
 	/**
 	 * 获取图片宽度
 	 * @param string $image
 	 * @return int
 	 */
 	public static function getImageWidth($image) {
 		$imageInfo = getimagesize($image);
 		return isset($imageInfo[0])? $imageInfo[0] : 0;
 	}
 	
 	/**
 	 * 获取图片高度
 	 * @param string $image
 	 * @return int
 	 */
 	public static function getImageHeight($image) {
 		$imageInfo = getimagesize($image);
 		return isset($imageInfo[1])? $imageInfo[1] : 0;
 	}
 	
 	/**
 	 * 获取图片类型
 	 * @param unknown $image
 	 */
 	public static function getPicMimeType($image) {
 		//获取图片类型
 		$imageInfo = getimagesize($image);
 		$type = $imageInfo[2];
 		$extension = image_type_to_extension($type, FALSE);
 		if($extension == 'jpeg') {
 			$extension = 'jpg';
 		}
 		$mime = image_type_to_mime_type($type);
 		return $mime;
 	} 
 	
 	/**
 	 * 将已有图片拷贝并裁剪为相应尺寸
 	 * @param string $sourcePicFilePath
 	 * @param string $targetPicFilePath
 	 * @param int $targetWidth
 	 * @param int $targetHeight
 	 * @param stinng $mater
 	 * @return string
 	 */
 	public static function copyAndResizePic($sourcePicFilePath = '', $targetPicFilePath = '', $targetWidth = 0, $targetHeight = 0, $mater = NULL) {
 		$sourceFileContent = file_get_contents($sourcePicFilePath);
 		if( FALSE != file_put_contents($targetPicFilePath, $sourceFileContent)) {
 			if(!$targetHeight && !$targetWidth) {
 				return $targetPicFilePath;
 			}
 			$image = Image_vmsImage::factory($targetPicFilePath);
 			$sourceWidth = $image->width;
 			$sourceHeight = $image->height;
 			//原图片宽高比大于目标图片宽高比
 			if($sourceWidth/$sourceHeight > $targetWidth/$targetHeight) {
 				//图片过宽
 				$scropedHeight = $sourceHeight;
 				$scropedWidth = $scropedHeight * ($targetWidth / $targetHeight);
 				$x = ($sourceWidth - $scropedWidth) / 2;
 				$y = 0;
 			} else {
 				//原图片宽高比小于等于目标图片宽高比
 				//图片过高
 				$scropedWidth = $sourceWidth;
 				$scropedHeight = $scropedWidth * ($targetHeight / $targetWidth);
 				$x = 0;
 				$y = ($sourceHeight - $scropedHeight) / 2;
 			}
 			$result = $image->crop($scropedWidth, $scropedHeight, $x, $y)
 				->resize($targetWidth, $targetHeight)
 				->save($targetPicFilePath);
 			if($result){
 				return $targetPicFilePath;
 			}
 			return NULL;
 		} 
 		return NULL;
 	}
 	
 	/**
 	 * 按照指定的坐标和宽高截取图片内容
 	 * @param string $sourcePicUrl 原图片地址
 	 * @param string $targetImagePath 目标图片地址
 	 * @param int $x 截图x偏移
 	 * @param int $y 截图y偏移
 	 * @param int $w 截图宽度
 	 * @param int $h 截图高度
 	 * @return Bool
 	 */
 	public static function cropImage($sourcePicUrl, $targetImagePath, $x, $y, $w, $h) {
 		$sourceWidth =  Image_vmsImage::getImageWidth($sourcePicUrl);
 		$sourceHeight = Image_vmsImage::getImageHeight($sourcePicUrl);
 		$jpeg_quality = 90;
 		//创建画布
 		$sourceImage = imagecreatefromjpeg($sourcePicUrl);
 		$targetImage = ImageCreateTrueColor( $sourceWidth, $sourceHeight );
 		//截取图片
 		imagecopyresampled($targetImage, $sourceImage, 0, 0, $x, $y, $sourceWidth, $sourceHeight, $w, $h);
 		//返回截取后的图片
 		return imagejpeg($targetImage, $targetImagePath, $jpeg_quality);
 	}

	/**
	 * base64转为图片
	 * @param string $base64String
	 * @param string $outputFile
	 */
	public static function base64ToJpeg($base64String, $outputFile) {
		$fp = fopen($outputFile, "wb"); 
		$data = explode(',', $base64String);
		fwrite($fp, base64_decode($data[1])); 
		fclose($fp);
	}
}
