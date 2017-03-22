<?php
/**
 * Base62 将整型转为62进制数字（有大数问题）
 * @author Sundj
 * @since 2014.04.07
 */
class Base62 {
	
	const BASE = 62;
	
	static $baseChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	
	public function encode($number) {
		$output = '';
		
		do {
			$reminder = $number % Base62::BASE;
			$output = Base62::$baseChars[$reminder] . $output;
			$number = ($number - $reminder) / Base62::BASE;
		} while($number > 0);
		
		return $output;
	}
	
	public function decode($input) {
		$length = strlen($input);
		
		$number = 0;
		$baseChars = array_flip(str_split(Base62::$baseChars));
		for($i = 0; $i < $length; ++$i) {
			$number += $baseChars[$input[$i]] * pow(Base62::BASE, $length - $i - 1);
		}
		return number_format($number, 0, '', '');
	}
}