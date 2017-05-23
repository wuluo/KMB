<?php

/**
 * Class Utils
 * 静态工具类
 */
final class Utils
{
	/**
	 * 字数截取函数，支持汉字
	 * @param $text
	 * @param $length
	 * @return string
	 */
	public static function subtext($text, $length)
	{
		if(mb_strlen($text, 'utf8') > $length)
			return mb_substr($text, 0, $length, 'utf8').'...';
		return $text;
	}

	/**
	 * 返回时间长度的小时分秒格式
	 *
	 * @param integer $timeLength
	 * @param boolean $millisecond
	 * @return string
	 */
	public static function time_span($timeLength, $millisecond = TRUE) {
		$timeLength = ($millisecond === TRUE) ? (int) $timeLength * 0.001 : (int) $timeLength;

		$hours = 0;
		$minutes = 0;
		$seconds = 0;
		if($timeLength >= Date::HOUR) {
			$hours = floor($timeLength / Date::HOUR);
			$timeLength = $timeLength % Date::HOUR;
		}
		if($timeLength >= Date::MINUTE) {
			$minutes = floor($timeLength / Date::MINUTE);
			$timeLength = $timeLength % Date::MINUTE;
		}
		$seconds = floor($timeLength);
		if($hours == 0){
			return sprintf("%02d:%02d", $minutes, $seconds);
		}else{
			return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
		}
	}

	/**
	 * 返回一个时间点和现在时间的间隔
	 *
	 * @param   integer $timestamp          "remote" timestamp
	 * @param   integer $local_timestamp    "local" timestamp, defaults to time()
	 * @return  string
	 */
	public static function fuzzy_span($timestamp, $local_timestamp = NULL)
	{
		$local_timestamp = ($local_timestamp === NULL) ? time() : (int) $local_timestamp;

		$offset = $local_timestamp - $timestamp;
		$year = date('Y', $timestamp);
		$local_year = date('Y', $local_timestamp);
		if ($offset < Date::MINUTE)
		{
			//$span = ceil($offset / 10) * 10 .'秒前';
			$span ='刚刚发布';
		}
		elseif ($offset < Date::MINUTE)
		{
			$span = ceil($offset / Date::MINUTE).'分钟前';
		}
		elseif ($offset < Date::HOUR)
		{
			$span = floor($offset / Date::MINUTE).'分钟前';
		}
		elseif ($offset < Date::DAY)
		{
			$span = floor($offset / Date::HOUR).'小时前';
		}
		elseif ($offset < 30 * Date::DAY)
		{
			$span = floor($offset / Date::DAY).'天前';
		}
		elseif ($year == $local_year)
		{
			$span = date('n月j日 H:i', $timestamp);
		}
		else
		{
			$span = date('Y年n月j日 H:i', $timestamp);
		}

		/*if ($timestamp > $local_timestamp)
		{
			return date('Y年n月j日 H:i', $timestamp);
		}*/

		return $span;
	}
	/**
	 * @description : 特殊字符处理(包括空格显示问题)
	 * @notice: 在模板中因连续空格,只能显示一个,故封装此方法
	 * 同时对特殊字符以及空格显示问题做处理
	 *
	 * @param : [string] - $string
	 * @return string
	 */
	public static function specialChars($string= '')
	{
		return nl2br(str_replace(' ','&nbsp;',htmlspecialchars($string)));
	}

	/**
	 * @description : onerror 失效图片链接替换
	 * @notice : 仅为解决某种机型onerror 不兼容问题
	 * @param  : [string] - $url 来源
	 * @param  : [string] - $replaceUrl 被替换的
	 * @return : string
	 */
	public static function replaceUrl($url='',$replaceUrl='')
	{
		if($url) return $url;
		return $replaceUrl;
	}

	/**
	 * 获取毫秒时间戳
	 * @return float
	 */
	public static function getMillisecond()
	{
		list ($s1, $s2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
	}

	/**
	 * @param $data
	 * @return bool
	 */
	public static function isJson($data)
	{
		json_decode($data);
		return json_last_error() == JSON_ERROR_NONE;
	}
}
