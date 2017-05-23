<?php

/**
 * 记录异常日志
 * @author 
 */
class Log_Database extends Log_Writer {

        static protected $_DB = NULL;
        protected $_connection = array(
                'hostname' => '127.0.0.1',
                'database' => '',
                'username' => '',
                'password' => '',
                'persistent' => FALSE,
        );
        protected $_charset = 'utf8';
        protected $_table = '';

        public function __construct() {
                $this->_table = Kohana::$config->load("logger_crash.custom.table");
                $this->_connection = Kohana::$config->load("logger_crash.custom.connection");
                $this->_charset = Kohana::$config->load("logger_crash.custom.charset");
        }

        public function write(array $messages) {
                $this->_connect();
                foreach ($messages as $message) {
                        $this->_write($message);
                }
        }

        protected function _connect() {
                if (self::$_DB !== NULL) {
                        return $this;
                }
                if ($this->_connection['persistent']) {
                        self::$_DB = new PDO($this->_connection['dsn'], $this->_connection['username'], $this->_connection['password'], array(PDO::ATTR_PERSISTENT => true));
                } else {
                        self::$_DB = new PDO($this->_connection['dsn'], $this->_connection['username'], $this->_connection['password'], array(PDO::ATTR_PERSISTENT => false));
                }
                return $this;
        }

        protected function _write(array $message) {
                $postParams = $_POST ? json_encode($_POST) : (file_get_contents("php://input") ? file_get_contents("php://input") : json_encode(array()));
                $values = array(
                        'level' => $message['level'],
                        'ip' => isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : '',
                        'hostname' => isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : '',
                        'uri' => isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '',
                        'server' => json_encode(array(
                                'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                                'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
                                'requestType' => $this->is_https() ? 'https' : 'http',
                                'remoteAddr' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                        )),
                        'cookie' => $_COOKIE ? json_encode($_COOKIE) : json_encode(array()),
                        'post' => $postParams,
                        'get' => $_GET ? json_encode($_GET) : (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''),
                        'file' => $message['file'],
                        'line' => $message['line'],
                        'message' => $message['body'],
                        'create_time' => $message['time'],
                );
                if (isset($message['additional']['exception'])) {
                        if ($values['file'] === NULL) {
                                $values['file'] = $message['additional']['exception']->getFile();
                        }
                        if ($values['line'] === NULL) {
                                $values['line'] = $message['additional']['exception']->getLine();
                        }
                }
                $values['file'] = str_replace("\\", "/", $values['file']);
                $values['message'] = str_replace("\\", "/", $message['body']);
                $columns = array_keys($values);

                $columns = "`" . implode("`,`", $columns) . "`";
                $values = "'" . implode("','", $values) . "'";

                $query = "INSERT INTO {$this->_table} ($columns) VALUES ($values)";
                $result = self::$_DB->exec($query);

                return array(self::$_DB->lastInsertId(), 1);
        }

        /*
         * 检测链接是否是https连接
         * @return bool
         */

        private function is_https() {
                if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
                        return TRUE;
                } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                        return TRUE;
                } elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
                        return TRUE;
                }
                return FALSE;
        }

}
