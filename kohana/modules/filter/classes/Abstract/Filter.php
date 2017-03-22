<?php

/**
 * 过滤器接口
 * 所有的过滤器键都是$filterName
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2016/7/28
 * @time:   10:45
 */
class Abstract_Filter {

	private static $filterInstance = array();//已经存在的过滤器
	private static $filterEvent = array();//需要执行的过滤事件
	private static $selfIntance = null;
	protected static $recordLog = false;//是否记录错误日志，@TODO

	private function __Construct() {
	}

	/**
	 * 获取过滤器实例
	 * @return null
	 */
	public static function getInstance() {
		if (Abstract_Filter::$selfIntance instanceof Abstract_Filter || Abstract_Filter::$selfIntance == null) {
			Abstract_Filter::$selfIntance = new Abstract_Filter();
		}
		return Abstract_Filter::$selfIntance;
	}

	/**
	 * 绑定一个过滤器事件
	 * @param $filterName 过滤器键，例如：request,则对应实例化Helper_RequestFilter
	 * @param $method 调用的方法数组
	 * @return boolean
	 */
	public function bindFilterEvent($filterName, $method, $param = array()) {
		$filter = $this->getFilterInsatnce($filterName);
		if ($filter == null) {
			$filterClassName = "Helper_" . ucwords($filterName) . "Filter";
			$filter = new $filterClassName();
			$this->saveFilterInstance($filterName, $filter);
		}

		//绑定事件
		if (!isset(Abstract_Filter::$filterEvent[$filterName][$method])) {
			Abstract_Filter::$filterEvent[$filterName][$method] = $param;//过滤器名->过滤器方法参数
		}
		return $this;
	}

	/**
	 * 执行当前过滤器的过滤事件
	 * @return [
	 *  'result' => true,
	 *  'message'=> '拦截原因'
	 * ]
	 */
	public function exec() {
		$exception = [];//执行异常
		if (is_array(Abstract_Filter::$filterEvent)) {
			foreach (Abstract_Filter::$filterEvent as $event => $value) {
				$eventInstance = $this->getFilterInsatnce($event);
				if (!empty($value)) {
					foreach ($value as $method => $param) {
						if (!method_exists($eventInstance, $method)) {
							$exception[] = "调用方法不存在：{$event}->{$method}";
							//@TODO::这里记录异常日志
						} else {
							try {
								call_user_func_array([$eventInstance, $method], $param);
							} catch (Business_Exception $e) {
								throw $e;
//								return $excResult = $e->getMessage();
							}
						}
					}
				}
			}
		}
		return true;
	}


	/**
	 * 获取过滤器事件的对应实例
	 * @param $key
	 * @return mixed|null
	 */
	private function getFilterInsatnce($key) {
		if (!isset(Abstract_Filter::$filterInstance[$key])) {
			return null;
		}
		return Abstract_Filter::$filterInstance[$key];
	}

	/**
	 * 保存过滤器事件的对应实例
	 * @param $key
	 * @param $intance
	 * @return bool
	 */
	private function saveFilterInstance($key, $intance) {
		if (!isset(Abstract_Filter::$filterInstance[$key])) {
			Abstract_Filter::$filterInstance[$key] = $intance;
		}
		return true;
	}

	public function bindGlobalResultVar($vaName) {

	}
}