<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * PHP session class use Memchache.
 *
 * @package    Kohana
 * @category   Session
 * @copyright  (c) 2014 Sina Video Team
 * @license    NULL
 */
class Kohana_Session_Memcache extends Session {

	protected $_servers = array (
			array (
				'host' => 'localhost',
				'port' => '11211',
				'persistent' => FALSE,
				'weight' => 1,
				'timeout' => 5,
			),
		);

	protected $_timeout = 5;

	protected $_persistent = FALSE;

	protected $_memcache = NULL;

	private $_session_id = NULL;

	public function __construct(array $config = NULL, $id = NULL) {

		if(isset($config['servers'])) {
			$this->_servers = $config['servers'];
		}

		if(!class_exists('Memcache')) {
			throw new Session_Exception('Class Memcache does not exist');
		}
		$this->_memcache = new Memcache();

		foreach($this->_servers as $server) {
			$host = $server['host'];
			$port = $server['port'];
			$weight = $server['weight'];
			$persistent = $server['persistent'];
			$timeout = $server['timeout'];
			$this->_memcache->addserver($host, $port, $persistent, $weight, $timeout);
		}
		
		parent::__construct($config, $id);
	}

	/**
	 * @return string
	 */
	public function id() {
		return $this->_session_id;
	}

	/**
	 * @param  $id session id
	 * @return array|null|string
	 */
	protected function _read($id = NULL) {
		if($id OR $id = Cookie::get($this->_name)) {
			$this->_session_id = $id;
			$data = $this->_memcache->get($this->_session_id);
			if(!$data) {
				return NULL;
			}
			$data = $this->_decode($data);
			return $this->_data = $this->_unserialize($data);
		}
		// create new session id
		$this->_regenerate();
		return NULL;
	}

	/**
	 * regenerate session_id
	 * @return mixed|string
	 */
	protected function _regenerate() {
		return $this->_session_id = str_replace('.', '-', uniqid(NULL, TRUE));
	}

	/**
	 * @return boolean
	 */
	protected function _write() {
		$data = $this->_serialize($this->_data);
		$data = $this->_encode($data);
		//write and close the session
		$this->_memcache->set($this->_session_id, $data, 0, $this->_lifetime);
		Cookie::set($this->_name, $this->_session_id, $this->_lifetime);
		return TRUE;
	}

	/**
	 * @return boolean
	 */
	protected function _restart() {
		$this->_destroy();
		$this->_regenerate();
		return TRUE;
	}

	/**
	 * @return boolean
	 */
	protected function _destroy() {
		// Make sure the session cannot be restarted
		Cookie::delete($this->_name);
		$this->_memcache->delete($this->_session_id);
		return TRUE;
	}
}
