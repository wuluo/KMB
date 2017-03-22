<?php defined('SYSPATH') OR die('No direct script access.');

class Image_GD extends Kohana_Image_GD {
	
	/**
	 * 获取上传图片的图片类型
	 * @param string $imageFile
	 * @return string
	 */
	public static function getImageType($imageFile) {
		$ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
		return $ext;
	}
	
	/**
	 * 获取HTTP图片链接的图片类型
	 * @param string $imageFile
	 * @return string
	 */
	public static function getHttpImageType($imageFile) {
		$ext = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
		return $ext;
	}
}
