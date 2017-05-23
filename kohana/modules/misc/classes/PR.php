<?php
/**
 * 概率算法
 * @author: panchao
 */
class PR {

	/**
	 * 经典抽奖算法1
	 * @param array $data
	 * ['a' => 10, 'b' => 20, 'c' => 50]
	 * @return int
	 */
	public static function luckDrawOne(array $data = []) {
		$result = '';
		$intervalNumber = array_sum($data);
		foreach ($data as $id => $weight) {
			$randNumber = mt_rand(1, $intervalNumber);
			if($randNumber <= $weight) {
				$result = $id;
				break;
			}else {
				$intervalNumber -= $weight;
			}
		}

		unset($data);
		return $result;
	}

	/**
	 * 经典抽奖算法 2 (1的另一实现)
	 * @param array $data
	 * ['a' => 10, 'b' => 20, 'c' => 50]
	 * @return int
	 */
	public static function luckDrawTwo(array $data = []) {
		$result = '';

		$intervalNumber = array_sum($data);
		$randNumber = mt_rand(1, $intervalNumber);
		$randStart = 0;

		foreach ($data as $id => $weight) {

			if($randNumber <= $randStart + $weight) {
				$result = $id;
				break;
			} else {
				$randStart += $weight;
			}
		}

		unset($data);
		return $result;
	}
}