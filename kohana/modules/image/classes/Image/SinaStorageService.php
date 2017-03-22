<?php
/**
 * SinaStorageService
 *
 * @author: quyang1@staff.sina.com.cn
 * @date: 2011-05-05
 * @version: 0.1 Beta
 */

require_once("SinaService.php");

/**
 * SinaStorageService Class
 *
 * A encapsulation of SinaStorageService.
 * Client Docs: http://wiki.internal.sina.com.cn/moin//DAppCluster/DappClusterReadme2/DBA-Announce20110523
 * Original Docs: http://sinastorage.sinaapp.com/developer/interface/aws/operate_object.html
 */
class Image_SinaStorageService extends Image_SinaService {
	static $domain = "http://sinastorage.com/";

	const UPDOMAIN = "http://up.sinastorage.com/";
	const HTTP_STATUS_OK = 200;
	const HTTP_STATUS_NO_CONTENT = 204;

	/**
	 * The maximum number of seconds to allow CURL functions to execute.
	 */
	const CURL_TIMEOUT = 10;

	/**
	 * Singelton pool
	 *
	 * @var array of objects
	 */
	static $objects_pool;

	public $result_info;

	private $project;
	private $access_key;
	private $secret_key;

	/**
	 * Request expire.
	 */
	private $expires = 0;

	/**
	 * Whether need auth.
	 */
	private $need_auth = false;

	/**
	 * Extra articular action.
	 * Case sensitive.
	 */
	private $extra = "?";
	private $extra_array = array("copy", "acl", "location", "logging", "op",
								"relax", "meta", "torrent", "ip",
								"uploadId", "uploads", "partNumber");
	private $kv_extra_array = array("op", "partNumber", "uploadId", "ip");

	/**
	 * Set query strings
	 *
	 * Query string should be formed like this:
	 * array(
	 * 		"foo" 	=> "1304563262",
	 * 		"bar" 	=> "ignore",
	 * );
	 */
	protected $query_strings = array();

	/**
	 * Request headers
	 *
	 * should be formed like this:
	 * array(
	 * 		"Content-Type" => "text/plain",
	 * 		"Content-Length" => "11",
	 * 		"Content-MD5" => "XrY7u+Ae7tCTyyK7j1rNww==",
	 * );
	 */
	protected $request_headers = array();

	/**
	 * cURL Options.
	 *
	 * The array should like this:
	 * 		array(CURLOPT_HTTPHEADER=>1,CURLOPT_RETURNTRANSFER=>0);
	 * 		!!NOT!!:
	 * 		array("CURLOPT_HTTPHEADER"=>1,"CURLOPT_RETURNTRANSFER"=>0);
	 */
	protected $curlopts = array();


	/**
	 * Constructor
	 */
	public function __construct($project, $access_key, $secret_key){
		if(empty($project)){
			throw new Image_SinaServiceException("Project MUST present as a string.");
		} else {
			$this->project = $project;
		}
		$this->access_key = $access_key ? $access_key : NULL ;
		$this->secret_key = $secret_key ? $secret_key : NULL ;
	}

	/**
	 * Get the singelton of this class.
	 *
	 * @param string $project  Project name.
	 * @param string $access_key [optional]  The access_key.
	 * @param string $secret_key [optional]  The secret_key.
	 * @param bool $renew [optional]  Sometimes you may want a new instance.
	 * @return object
	 */
	public static function getInstance($project, $access_key = NULL, $secret_key = NULL, $renew = false){
		if(($access_key && !$secret_key) || (!$access_key && $secret_key)){
			throw new Image_SinaServiceException("Access_key and secret_key MUST both present or absent.");
		}
		$key = sprintf("%s_%s_%s", $project, $access_key, $secret_key);
		if(!$renew){
			$object = isset(self::$objects_pool[$key]) ? self::$objects_pool[$key] : NULL ;
			if(is_object($object) && get_class($object) === __CLASS__){
				return $object;
			}
		}
		return self::$objects_pool[$key] = new self($project, $access_key, $secret_key);
	}


	/**
	 * get upload idc.
	 *
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function getuploadIdc($result = NULL){
		self::$domain = self::UPDOMAIN;
		$url = self::$domain;
		$this->setExtra("?extra&op=domain.json");
		list($result, $result_info) = $this->cURL($url, "GET");
		return $result_info['http_code'] == self::HTTP_STATUS_OK;
	}

	/**
	 * get uploadId.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $file_mimetype  File content type.
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function getuploadId($dest_name, $file_mimetype = NULL, $result = NULL){
		self::$domain = self::UPDOMAIN;
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->setExtra("?uploads");
		if($file_mimetype != NULL){
			$this->request_headers['Content-Type'] = $file_mimetype;
		}
		list($result, $result_info) = $this->cURL($url, "POST");
		return $result_info['http_code'] == self::HTTP_STATUS_OK;
	}

	/**
	 * Upload part.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $file_content
	 * @param int $file_size
	 * @param string $file_mimetype
	 * @param string $part_number
	 * @param string $upload_id  Upload ID
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function uploadPart($dest_name, $file_content, $file_size, $file_mimetype, $part_number, $upload_id, $result = NULL){
		self::$domain = self::UPDOMAIN;
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->setExtra("?");
		$this->setQueryStrings(array(
			"partNumber"	=> "$part_number",
			"uploadId"		=> "$upload_id",
		));
		$this->request_headers['Content-Length'] = $file_size;
		$this->request_headers['Content-Type'] = $file_mimetype;
		$this->setCURLOPTs(array(
			CURLOPT_POSTFIELDS	=>	$file_content,
			CURLOPT_HEADER		=>	1,
		));
		list($result, $result_info) = $this->cURL($url, "PUT");
		return $result_info['http_code'] == self::HTTP_STATUS_OK;
	}

	/**
	 * Get upload parts
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $upload_id Upload ID
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function getuploadParts($dest_name, $upload_id, $result = NULL){
		self::$domain = self::UPDOMAIN;
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->setExtra("?");
		$this->setQueryStrings(array(
			"uploadId"		=> "$upload_id",
		));
		list($result, $result_info) = $this->cURL($url, "GET");
		return $result_info['http_code'] == self::HTTP_STATUS_OK;
	}

	/**
	 * marge part.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $merge_content
	 * @param int $file_size
	 * @param string $file_mimetype
	 * @param string $upload_id
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function mergeParts($dest_name, $merge_content, $file_size, $file_mimetype, $upload_id, $result = NULL){
		self::$domain = self::UPDOMAIN;
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->setExtra("?");
		$this->setQueryStrings(array(
			"uploadId"		=> "$upload_id",
		));
		$this->request_headers['Content-Length'] = $file_size;
		$this->request_headers['Content-Type'] = $file_mimetype;
		$this->setCURLOPTs(array(
			CURLOPT_POSTFIELDS	=>	$merge_content,
			CURLOPT_HEADER		=>	1,
		));
		list($result, $result_info) = $this->cURL($url, "POST");
		return $result_info['http_code'] == self::HTTP_STATUS_OK;
	}

	/**
	 * Upload file.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $file_content
	 * @param int $file_size
	 * @param string $file_mimetype
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function uploadFile($dest_name, $file_content, $file_size, $file_mimetype, $result = NULL){
		if( self::$domain == "http://sinastorage.com/" ){
			self::$domain = "http://sinastorage.com/";
		}
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->request_headers['Content-Length'] = $file_size;
		$this->request_headers['Content-Type'] = $file_mimetype;
		$this->setCURLOPTs(array(
			CURLOPT_POSTFIELDS	=>	$file_content,
			CURLOPT_HEADER		=>	1,
		));
		return $this->doCURL($url, "PUT", $result);
	}

	/**
	 * Upload file relax mode.
	 * All we need is just sha1 digest and length of the file that existed on our server.
	 * Original docs(UTF-8) from SinaStorage:
	 * REST型PUT上传，但不上传具体的文件内容。而是通过SHA-1值对系统内文件进行复制。
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $file_sha1  sha1 digest of the file .
	 * @param int $file_size  length of the file.
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function uploadFileRelax($dest_name, $file_sha1, $file_size, $result = NULL){
		$url = self::$domain . $this->project . "/" . $dest_name;
		if($this->extra == "?"){
			$this->setExtra("?relax");
		}
		$this->request_headers['Content-Length'] = 0;
		$this->request_headers['s-sina-sha1'] = $file_sha1;
		$this->request_headers['s-sina-length'] = $file_size;
		$this->setCURLOPTs(array(
			CURLOPT_HEADER		=>	1,
		));
		return $this->doCURL($url, "PUT", $result);
	}

	/**
	 * Copy file.
	 *
	 * Original docs(UTF-8) from SinaStorage:
	 * REST型COPY，不上传具体的文件内容。而是通过COPY方式对系统内另一文件进行复制。
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $src_name  Srcfile path.
	 * @param string $result  If failure, you may need check this out for reasons.
	 */
	public function copyFile($dest_name, $src_name, $result = NULL){
		$url = self::$domain . $this->project . "/" . $dest_name;
		if($this->extra == "?"){
			$this->setExtra("?copy");
		}
		$this->request_headers['Content-Length'] = 0;
		$this->request_headers['x-amz-copy-source'] = "/" . $this->project . "/" . $src_name;
		$this->setCURLOPTs(array(
			CURLOPT_HEADER		=>	1,
		));
		return $this->doCURL($url, "PUT", $result);
	}

	/**
	 * Copy file between two projects.
	 *
	 * Original docs(UTF-8) from SinaStorage:
	 * REST型COPY，不上传具体的文件内容。而是通过COPY方式对系统内另一文件进行复制。
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $src_proj  SrcProject path.
	 * @param string $src_name  Srcfile path.
	 * @param string $result  If failure, you may need check this out for reasons.
	 */
	public function copyFileBetweenProject($dest_name, $src_proj, $src_name, $result = NULL){
		$url = self::$domain . $this->project . "/" . $dest_name;
		if($this->extra == "?"){
			$this->setExtra("?copy");
		}
		$this->request_headers['Content-Length'] = 0;
		$this->request_headers['x-amz-copy-source'] = "/" . $src_proj . "/" . $src_name;
		$this->setCURLOPTs(array(
				CURLOPT_HEADER		=>	1,
		));
		return $this->doCURL($url, "PUT", $result);
	}

	/**
	 * Get file from SinaStorage.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $result  Retrieved data.
	 * @return bool
	 */
	public function getFile($dest_name, $result){
		$url = self::$domain . $this->project . "/" . $dest_name;
		return $this->doCURL($url, "GET", $result);
	}

	/**
	 * Get file URL.
	 * May be a usage of <img src="$var">
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $result  Retrieved data.
	 * @return bool
	 */
	public function getFileUrl($dest_name, $result, $requestMethod = "GET"){
		if(empty($dest_name)) return false;
		$url = self::$domain . $this->project . "/" . $dest_name;
		$result = $this->cURL($url, $requestMethod, true);
		return true;
	}

	/**
	 * Delete file.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function deleteFile($dest_name, $result = NULL){
		$url = self::$domain . $this->project . "/" . $dest_name;
		return $this->doCURL($url, "DELETE", $result, self::HTTP_STATUS_NO_CONTENT);
	}

	/**
	 * Get file meta.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function getMeta($dest_name, $result){
		$url = self::$domain . $this->project . "/" . $dest_name;
		$this->setExtra("?meta");
		return $this->doCURL($url, "GET", $result);
	}

	/**
	 * Update file meta.
	 * You may use $this->setRequestHeaders set headers for update file meta.
	 *
	 * @param string $dest_name  Destination file name.
	 * @param string $result  If failure, you may need check this out for reasons.
	 * @return bool
	 */
	public function updateMeta($dest_name, $result = NULL){
		$url = self::$domain . $this->project . "/" . $dest_name;
		if($this->extra == "?"){
			$this->setExtra("?meta");
		}
		return $this->doCURL($url, "PUT", $result);
	}

	/**
	 * Get file list.
	 *
	 * @param string $result  Retrieved data.
	 * @return bool
	 */
	public function getFileList($result){
		$url = self::$domain . $this->project . "/";
		if($this->extra == "?"){
			$this->setExtra("?formatter=json");
		}
		return $this->doCURL($url, "GET", $result);
	}

	/**
	 * List files
	 * @param string $marker
	 * @param int $pageeach
	 * @param string $prefix
	 * @return array
	 */
	public function listFiles($marker, $pageeach, $prefix=''){
		$url = self::$domain . $this->project . "/";
		$query_strings = array(
			'formatter'	=> 'json',
			'marker'	=> $marker,
			'max-keys'	=> $pageeach,
			'prefix'	=> $prefix,
		);
		$this->setQueryStrings($query_strings);
		$res = $this->cURL($url, "GET");
		if ($res[1]['http_code'] == self::HTTP_STATUS_OK){
			return json_decode($res[0], true);
		}
	}

	/**
	 * List files more verbosely, and return json format result
	 * @param string $marker
	 * @param int $pageeach
	 * @param string $prefix
	 * @param string $result  Retrieved data.
	 * @return array
	 */
	public function listProjectFiles($marker, $pageeach, $prefix, $result){
		$url = self::$domain . $this->project . "/";
		$query_strings = array(
			'formatter'	=> 'json',
			'marker'	=> $marker,
			'max-keys'	=> $pageeach,
			'prefix'	=> $prefix,
		);
		$this->setQueryStrings($query_strings);
		return $this->doCURL($url, "GET", $result);
	}


	/**
	 * Set if do authorization.
	 *
	 * @param bool $do_auth
	 */
	public function setAuth($do_auth = true){
		$this->need_auth = $do_auth;
	}

	/**
	 * Set request expire.
	 *
	 * @param int $time
	 */
	public function setExpires($time = 0){
		$this->expires = $time;
	}

	/**
	 * Set extra action.
	 *
	 * For example "?acl", "?location", "?logging", "?relax", "?meta", "?torrent" or "?uploadID=...", "?ip=..."
	 */
	public function setExtra($extra = "?"){
		$this->extra = $extra;
	}

	/**
	 * Set query strings.
	 *
	 * @param array $query_strings  Should be formed like this:
	 * array(
	 * 		"foo" 	=> "1304563262",
	 * 		"bar" 	=> "ignore",
	 * );
	 */
	public function setQueryStrings(array $query_strings){
		if(count($this->query_strings) > 0){
			$this->query_strings = $query_strings + $this->query_strings;
		} else {
			$this->query_strings = $query_strings;
		}
	}

	/**
	 * Set request headers.
	 *
	 * @param array $headers should be formed like this:
	 * array(
	 * 		"Content-Type" => "text/plain",
	 * 		"Content-Length" => "11",
	 * 		"Content-MD5" => "XrY7u+Ae7tCTyyK7j1rNww==",
	 * );
	 */
	public function setRequestHeaders(array $headers){
		if(count($this->request_headers) > 0){
			$this->request_headers = $headers + $this->request_headers;
		} else {
			$this->request_headers = $headers;
		}
	}

	/**
	 * Get request_headers
	 *
	 * @return array
	 */
	public function getRequestHeaders(){
		return $this->request_headers;
	}

	/**
	 * Custom set curlopt
	 *
	 * @param array $curlopts For custom curlopt
	 * 	CAUTION!!
	 * 	The array should like this:
	 * 		array(CURLOPT_HTTPHEADER=>1,CURLOPT_RETURNTRANSFER=>0);
	 * 	NOT LIKE THIS:
	 * 		array("CURLOPT_HTTPHEADER"=>1,"CURLOPT_RETURNTRANSFER"=>0);
	 */
	public function setCURLOPTs(array $curlopts = array()){
		if(count($this->curlopts) > 0){
			$this->curlopts = $curlopts + $this->curlopts;
		} else {
			$this->curlopts = $curlopts;
		}
	}

	public function setTimeout($timeout){
		$this->setCURLOPTs(array(
			CURLOPT_TIMEOUT => $timeout
		));
	}

	public function setDomain( $domain ){
		self::$domain = $domain;
	}

	/**
	 * Get curlopts
	 *
	 * @return array
	 */
	public function getCURLOPTs(){
		return $this->curlopts;
	}

	/**
	 * Purge parameters.
	 *
	 * Sometimes we do something continuously, then got an error "SignatureDoesNotMatch", use this prevent that.
	 * @return null
	 */
	public function purgeParams(){
		$this->expires		= 0;
		$this->need_auth	= false;
		$this->extra		= "?";
		$this->query_strings	= array();
		$this->request_headers	= array();
		$this->curlopts 	= array();
	}

	public function purgeReq(){
		$this->extra		= "?";
		$this->query_strings	= array();
		$this->request_headers	= array();
		$this->curlopts 	= array();
		self::$domain = "http://sinastorage.com/";
	}

	protected function doCURL($url, $method, $result = NULL, $httpCode = self::HTTP_STATUS_OK){
		list($result, $this->result_info) = $this->cURL($url, $method);
			return $this->result_info['http_code'] == $httpCode;
	}

	/**
	 * Signature for authorization.
	 *
	 * For more info plz visit http://sinastorage.sinaapp.com/developer/interface/aws/auth.html
	 *
	 * @param string $verb  GET,PUT,DELETE...
	 * @param string $resource
	 * @param int $expires
	 * @return array
	 */
	protected function signatureHeader($verb, $resource, $expires = 0) {
		$headers = array();
		$tmp_header = array_change_key_case($this->request_headers, CASE_LOWER);
		ksort($tmp_header);
		$stringToSign = '';
		$arrayToSign = array();
		$arrayToSign['HTTP-Verb'] = $verb;
		$arrayToSign['Content-MD5'] = '';
		$arrayToSign['Content-Type'] = '';
		$arrayToSign['Date'] = '';
		$arrayToSign['CanonicalizedAmzHeaders'] = array();
		//God forgive me ...
		list($resource,$query_array) = explode("?",$resource);
		parse_str($query_array, $query_array);
		if(count($query_array) > 0){
			foreach($query_array as $key => $value){
				if(!in_array($key, $this->extra_array)){
					unset($query_array[$key]);
				}
			}
			if(count($query_array) > 0){
				$tmp = "";
				foreach($query_array as $key => $value){
					//TODO
					if(in_array($key, $this->kv_extra_array)){
						$tmp .= "&{$key}={$value}";
					} else {
						$tmp .= "&{$key}";
					}
				}
				$resource .= "?" . ltrim($tmp, "&");
			}
		}
		$arrayToSign['CanonicalizedResource'] = $resource;

		if (isset($tmp_header['s-sina-sha1'])) {
			$arrayToSign['Content-MD5'] = $tmp_header['s-sina-sha1'];
		} elseif (isset($tmp_header['content-sha1'])) {
			$arrayToSign['Content-MD5'] = $tmp_header['content-sha1'];
		} elseif (isset($tmp_header['s-sina-md5'])) {
			$arrayToSign['Content-MD5'] = $tmp_header['s-sina-md5'];
		} elseif (isset($tmp_header['content-md5'])) {
			$arrayToSign['Content-MD5'] = $tmp_header['content-md5'];
		}

		if (isset($tmp_header['content-type'])) {
			$arrayToSign['Content-Type'] = $tmp_header['content-type'];
		}

		if (isset($tmp_header['date'])) {
			$arrayToSign['Date'] = $tmp_header['date'];
		} elseif ($expires > 0) {
			$arrayToSign['Date'] = $expires;
		}

		foreach ($tmp_header as $k => $v) {
			if ( strpos($k, 'x-amz-') === 0 || strpos($k, 'x-sina-') === 0 ) {
				$arrayToSign['CanonicalizedAmzHeaders'][] = $k.':'.$v."\n";
			}
		}
		if (!empty($arrayToSign['CanonicalizedAmzHeaders'])) {
			$arrayToSign['CanonicalizedAmzHeaders'] = join("", $arrayToSign['CanonicalizedAmzHeaders']);
		} else {
			$arrayToSign['CanonicalizedAmzHeaders'] = '';
		}

		$stringToSign = $arrayToSign['HTTP-Verb'] . "\n" . $arrayToSign['Content-MD5'] . "\n"
						. $arrayToSign['Content-Type'] . "\n" . $arrayToSign['Date'] . "\n"
						. $arrayToSign['CanonicalizedAmzHeaders'] . $arrayToSign['CanonicalizedResource'];

		$ssig = substr(base64_encode(hash_hmac("sha1", $stringToSign, $this->secret_key, true)), 5, 10);

		// uncomment this to debug
		/*
		print_r($stringToSign);
		print_r($arrayToSign);
		 */

		$access_key = strtolower($this->access_key);
		$tmp = explode("0", $access_key);
		$nation = $tmp[ 0 ];
		$uid = substr($access_key, -10);
		if ( $nation != "sae" ) {
			$uid = explode( "0", $uid );
			$uid = $uid[ count( $uid )-1 ];
		}
		$KID = $nation.",".$uid;

		return array($ssig, $KID);
	}

	/**
	 * cURL function
	 *
	 * @param string $url Request url
	 * @param string $type  For custom request method
	 * @param bool $return_url  Do nothing but return url.
	 * @return array
	 */
	protected function cURL($url, $type, $return_url = false){
		$headers = array();
		$url .= $this->extra;

		if(count($this->query_strings) > 0){
			foreach($this->query_strings as $key=>$value){
				$url .= "&{$key}={$value}";
			}
		}

		if(count($this->request_headers) > 0){
			foreach($this->request_headers as $key=>$value){
				$headers[] = "{$key}: {$value}";
			}
		}

		if($this->need_auth){
			$resource = str_replace(self::$domain, "/", $url);
			$exp = $this->expires > 0 ? $this->expires : time() + 7200;
			list($ssig, $KID) = $this->signatureHeader($type, $resource, $exp);
			$url .= sprintf("&ssig=%s&KID=%s&Expires=%d", urlencode($ssig), $KID, $exp);
		}

		if($return_url){
		//	return rtrim($url,"?");
			return $url;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if(count($this->curlopts) > 0){
			foreach($this->curlopts as $key => $value){
				curl_setopt($ch, $key, $value);
			}
		}
		$result = curl_exec($ch);
		$result_info = curl_getinfo($ch);
		if($result === false){
			throw new Image_SinaServiceException("CURL error occurred:".curl_error($ch));
		}
		curl_close($ch);
		$this->purgeReq();

		return array($result,$result_info);
	}
}

?>
