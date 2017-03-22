<?php
/**
 * 二维码，依赖开源二维码生成类
 * eg. 
 * 		$image = QRcode::instance()
 *			->data('This a test')
 *			->frame(3)
 *			->point(8)
 *			->color(255, 255, 255)
 *			->logo(/path/to/image)
 *			->execute()
 *			->printImage();
 * @see QRcode_Maker
 * @author Sundj
 */
class QRcode {

	/**
	 * 二维码数据矩阵
	 * @var array
	 */
	private $_barCodes = array();
	
	/**
	 * 二维码列数
	 * @var integer
	 */
	private $_columns = 1;
	
	/**
	 * 二维码行数
	 * @var integer
	 */
	private $_rows = 1;
	
	/**
	 * 二维码图
	 * @var source
	 */
	private $_targetImage = NULL;
	
	/**
	 * 点位像素
	 * @var integer
	 */
	protected $_pointPixels = 1;
	
	/**
	 * 图片边框留白
	 * @var integer
	 */
	protected $_framePixels = 1;
	
	/**
	 * 图片颜色(RGB)
	 * @var array
	 */
	protected $_color = array(0, 0, 0);
	
	/**
	 * 图片类型
	 * @var integer
	 */
	protected $_imageType = 'png';
	
	/**
	 * 水印LOGO
	 * @var string
	 */
	protected $_logo = NULL;
	
	/**
	 * 容错级别
	 * @var string
	 */
	protected $_level = 'L';
	
	
	protected $_mime = 'image/png';
	
	/**
	 * gd function
	 * @var string
	 */
	protected $_imageFunction = 'imagepng';
	
	/**
	 * 数据
	 * @var string
	 */
	protected $_data = '';
	
	
	static public function instance() {
		return new self();
	}
	
	private function __construct() {
		
	}
	
	public function data($data = '') {
		$this->_data = $data;
		return $this;
	}
	
	public function point($pixels = 4) {
		if(!is_numeric($pixels)) {
			throw new QRcode_Exception("QRcode::point() » '$pixels' invaild parameter.");
		}
		$this->_pointPixels = $pixels;
		return $this;
	}
	
	public function frame($pixels = 4) {
		if(!is_numeric($pixels)) {
			throw new QRcode_Exception("QRcode::frame() » '$pixels' invaild parameter.");
		}
		$this->_framePixels = $pixels;
		return $this;
	}
	
	/**
	 * 设置容错级别 L, M, Q, H
	 * @param string $level
	 * @return QRcode
	 */
	public function level($level = 'L') {
		$this->_level = $level;
		return $this;
	}
	
	/**
	 * 水印
	 * @param string $logo
	 * @return QRcode
	 */
	public function logo($logo = NULL) {
		$this->_logo = $logo;
		return $this;
	}
	
	/**
	 * 图片颜色(rgb)
	 * @param string $color
	 */
	public function color($red = 0, $green = 0, $blue = 0) {
		$this->_color = array($red, $green, $blue);
		return $this; 
	}
	
	public function image($type = 'png') {
		$type = strtolower($type);
		$this->_imageType = $type;
		
		if($type == 'png') {
			$this->_imageFunction = 'imagepng';
			$this->_mime = 'image/png';
		}
		if($type == 'gif') {
			$this->_imageFunction = 'imagegif';
			$this->_mime = 'image/gif';
		}
		if($type == 'jpg') {
			$this->_imageFunction = 'imagejpeg';
			$this->_mime = 'image/jpeg';
		}
		if($type == 'jpeg') {
			$this->_imageFunction = 'imagejpeg';
			$this->_mime = 'image/jpeg';
		}
		if($type == 'bmp') {
			$this->_imageFunction = 'imagewbmp';
			$this->_mime = 'image/bmp';
		}
		return $this;
	}
	
	public function execute() {
		$QRcodeMaker = new QRcode_Maker($this->_data, $this->_level);
		$codes = $QRcodeMaker->getBarcodeArray();
		
		$this->_columns = $codes['num_cols'];
		$this->_rows = $codes['num_rows'];
		$this->_barCodes = $codes['bcode'];
		

		$height = $this->_rows + 2 * $this->_framePixels;
		$width = $this->_columns + 2 * $this->_framePixels;
		
		$baseImage = ImageCreate($width, $height);
		
		$background =  ImageColorAllocate($baseImage, 255, 255, 255);
		$color =  ImageColorAllocate($baseImage, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		imagefill($baseImage, 0, 0, $background);
		
		for($y = 0; $y < $this->_columns; $y++) {
			for($x = 0; $x < $this->_rows; $x++) {
				if ($this->_barCodes[$y][$x] == '1') {
					ImageSetPixel($baseImage, $x + $this->_framePixels, $y + $this->_framePixels, $color);
				}
			}
		}
		
		$this->_targetImage = ImageCreate($width * $this->_pointPixels, $height * $this->_pointPixels);
		imagecopyresized($this->_targetImage, $baseImage, 0, 0, 0, 0, $height * $this->_pointPixels, $height * $this->_pointPixels, $width, $height);
		
		//添加LOGO
		if($this->_logo) {
			$logo = file_get_contents($this->_logo);
			$logoSize = getimagesize($this->_logo);
			$logoImage = imagecreatefromstring($logo);
			
			if($logoImage) {
				// 点阵宽度 x 点像素 * 1/5
				$width = $this->_rows * $this->_pointPixels * 1/5;
				$height = $this->_columns * $this->_pointPixels * 1/5;
				
				if($width > $logoSize[0]) {
					$width = $logoSize[0];
				}
				if($height > $logoSize[1]) {
					$height = $logoSize[1];
				}
				
				//((点阵宽度 ＋ 2x留白) x 点像素 － logo宽度)/ 2
				$logoX = (($this->_rows + 2 * $this->_framePixels) * $this->_pointPixels - $width) / 2;
				$logoY = (($this->_columns + 2 * $this->_framePixels) * $this->_pointPixels - $height) / 2;
				
				imagecopyresampled($this->_targetImage, $logoImage, $logoX, $logoY, 0, 0, $width, $height, $logoSize[0], $logoSize[1]);
			}
			
			imagedestroy($logoImage);
		}
		
		imagedestroy($baseImage);
		
		return $this;
	}
	
	public function getBarCodes() {
		return $this->_barCodes;
	}
	
	public function getImage() {
		return $this->_targetImage;
	}
	
	public function printImage() {
		$imageFunction = $this->_imageFunction;
		
		header("Content-type: {$this->_mime}");
		$imageFunction($this->_targetImage);
		exit();
	}
}