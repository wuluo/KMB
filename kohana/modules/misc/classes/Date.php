<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 继承框架Kohana_Date，重写模糊时间方法，返回中文时间值
 * Date helper.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Date extends Kohana_Date {

	const MILLISECOND = 0.001;

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

		return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
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

		if ($offset < Date::MINUTE - 10)
		{
			$span = ceil($offset / 10) * 10 .'秒前';
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
		elseif ($year == $local_year)
		{
			$span = date('n月j日 H:i', $timestamp);
		}
		else
		{
			$span = date('Y年n月j日 H:i', $timestamp);
		}

		if ($timestamp > $local_timestamp)
		{
			return date('Y年n月j日 H:i', $timestamp);
		}

		return $span;
	}
}