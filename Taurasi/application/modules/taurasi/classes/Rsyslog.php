<?php
/**
 * Created by PhpStorm.
 * User: guojianing
 * Date: 2017/3/14
 * Time: 16:19
 */
class Rsyslog {

    // 日志级别 从上到下，由低到高
    const EMERG     = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT     = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN      = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE    = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO      = 'INFO';  // 信息: 程序输出信息
    const DEBUG     = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    // 日志参数
    protected static $params  =  array(
        'project_name'  => 'banjo',   // 项目名称
        'level'          => '',      // 信息类型 error/debug/info/notice
        'method'        => 'file',  // 日志上传方式 file/http/redis/tcp
        'lbs_ip'        => '',      // 负载均衡ip
        'client_ip'     => '',      // 客户端ip
        'server_ip'     => '',      // 服务端ip
        'server_port'   => '',      // 服务端端口
        'domain'        => '',      // 服务器域名
        'referer'       => '',      // 上个页面
        'request_time'  => '',      // 请求时间
        'request_url'   => '',      // 请求url
        'request_method' => '',      // 请求方式get post
        'request_params' => '',      // 请求参数
        'request_trace_id' => '',   // 请求追踪id
        'user_agent'    => '',      // User-Agent
        'session_id'    => '',      // session_id
        'user_id'       => '',      // 用户id
        'content'       => '',      // 全部信息
    );

    // 实例化并传入参数
    private static function initialize($options)
    {
        //将传入的数据和已有的进行合并
        if($options)
        {
            foreach($options as $key => $value)
            {
                if(isset(self::$params[$key]))
                {
                    self::$params[$key] = $value;
                }
            }
        }
        self::$params['lbs_ip']         = $_SERVER['REMOTE_ADDR'];
        self::$params['client_ip']      = self::get_client_ip(0, true);
        self::$params['server_ip']      = $_SERVER['SERVER_ADDR'];
        self::$params['server_port']    = $_SERVER['SERVER_PORT'];
        self::$params['domain']        = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        self::$params['referer']        = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        self::$params['request_time']   = time();
        self::$params['request_url']    = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'].'://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        self::$params['request_method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $request_params = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;
        self::$params['request_params']  = http_build_query($request_params);
//        self::$params['request_trace_id'] = isset($_SERVER['HTTP_REQUEST_TRACE_ID']) ? $_SERVER['HTTP_REQUEST_TRACE_ID'] : '';
        self::$params['request_trace_id'] = self::traceIdGen();
        self::$params['user_agent']     = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * 日志写入接口
     * @access public
     * @param array $msg 日志信息
     * @param string $level  写入级别
     * @return void
     * demo Rsyslog::write($data, Rsyslog::ERR); $data：array
     */
    public static function write($msg = '', $level = Rsyslog::ERR,$options = array())
    {
        // 日志写入地址
        $destination = '/gomeo2o/logs/applog/banjo/app.log';
        $destination_bak = '/gomeo2o/logs/applog/banjo/' . date('Y-m') . '.log';
        // 自动创建日志目录
        $log_dir = dirname($destination);
        if (!is_dir($log_dir))
        {
            mkdir($log_dir, 0755, true);
        }

        // 按月备份日志
        if(! file_exists($destination_bak))
        {
            rename($destination, $destination_bak);
        }

        self::initialize($options);
        self::$params['level'] = $level;
        self::$params['content'] = self::text_format($msg);
        error_log(json_encode(self::$params, JSON_UNESCAPED_UNICODE)."\n", 3, $destination);
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    private static function get_client_ip($type = 0,$adv=false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * 对二级数组进行格式化,目的:
     * 1.防止中文,Rsyslog 送到es时报错
     * 2.避免太多字段在Kibana左侧展示
     * @param array $text
     * @return string
     */
    public static function text_format($text)
    {
        $text = self::array_filter_recursive($text);
        array_walk_recursive($text,'self::cb_es_fmt');
        return $text;
    }

    /**
     * 递归函数 如果数据里包含中文，则对数据的值进行urlencode
     * @param type array
     * return true
     * 用法：array_walk_recursive($data,"cb_es_fmt");
     */
    public static function cb_es_fmt(&$item,$key)
    {
        if(preg_match("/\p{Han}+/u", $item)){
            $item = urlencode($item);
        }
    }

    /**
     * 为了防止空值对Field datatypes造成影响，对空值进行unset
     * @param $var
     * @return bool
     */
    public static function array_filter_recursive($input)
    {
        if(is_array($input))
        {
            foreach ($input as &$value)
            {
                if (is_array($value))
                {
                    $value = self::array_filter_recursive($value);
                }
            }
        }
        return is_array($input) ? array_filter($input) : $input;
    }

    /**
     * 根据session id和当前时间生成traceId
     * @return string traceId
     */
    private static function traceIdGen()
    {
        $sessId = session_id();
        list($msec,$sec) = explode(' ',microtime());
        $timeStr = date('His').(int)($msec * 1000);
        return $sessId.$timeStr;
    }
}
