<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * PHP session class use redis.
 *
 * @package    Kohana
 * @category   Session
 * @author     dongjie
 * @copyright  (c) 2014 Sina Video Team
 * @license    NULL
 */
class Kohana_Session_Redis extends Session {
	
	protected $_server = '127.0.0.1';
	
	protected $_port = '6379';
	
	protected $_timeout = 30;
	
	protected $_persistent = FALSE;
	
	protected $_password = NULL;
	
	protected $_redis = NULL;
	
	private $_session_id = NULL;
	
	public function __construct(array $config = NULL, $id = NULL) {
		$this->_server = $config['server'];
		$this->_port = $config['port'];
		$this->_timeout = $config['timeout'];
		$this->_password = $config['password'];
		$this->_persistent = $config['persistent'];
		
		if(!class_exists('Redis')) {
			throw new Session_Exception('Class Redis does not exist');
		}
		
		$this->_redis = new Redis();
		if($this->_persistent) {
			$this->_redis->pconnect($this->_server, $this->_port, $this->_timeout);
		} else {
			$this->_redis->connect($this->_server, $this->_port, $this->_timeout);
		}
		
		if($this->_password) {
			$this->_redis->auth($this->_password);
		}
		
 		parent::__construct($config, $id);
	}

	/**
	 * @return  string
	 */
	public function id()
	{
		return $this->_session_id;
	}
	
	/**
	 * @param   string  $id  session id
	 * @return  null
	 */
	protected function _read($id = NULL)
	{
		if ($id OR $id = Cookie::get($this->_name))
		{
			$this->_session_id = $id;
			$data = $this->_redis->get($this->_session_id);
			if(!$data) {
				return NULL;
			}
			$data = $this->_decode($data);
			return $this->_data = $this->_unserialize($data);
		}

		// Create a new session id
		$this->_regenerate();
		return NULL;
	}
	
	/**
	 * @return  string
	 */
	protected function _regenerate()
	{
		// Regenerate the session id
		return $this->_session_id = str_replace('.', '-', uniqid(NULL, TRUE));
	}
	
	/**
	 * @return  bool
	 */
	protected function _write()
	{
		$data = $this->_serialize($this->_data);
		$data = $this->_encode($data);
		// Write and close the session
		if($this->_lifetime == 0) {
			$this->_redis->set($this->_session_id, $data);
		} else {
			$this->_redis->setex($this->_session_id, $this->_lifetime, $data);
		}
		Cookie::set($this->_name, $this->_session_id, $this->_lifetime);
		return TRUE;
	}
	
	/**
	 * @return  bool
	 */
	protected function _restart()
	{
		$this->_destroy();
		$this->_regenerate();
		return TRUE;
	}
	
	/**
	 * @return  bool
	 */
	protected function _destroy()
	{
		// Make sure the session cannot be restarted
		Cookie::delete($this->_name);
		$this->_redis->del($this->_name);
		return TRUE;
	}
	
}