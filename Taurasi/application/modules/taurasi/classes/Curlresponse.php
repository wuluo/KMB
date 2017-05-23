<?php
/**
 * RESTFUL资源返回类
 * 
 * @author  snow wolf
 * @date    2017-02-14
 * @version 1.0.0
 **/

class Curlresponse
{
    /*
     *  返回json数据资源
     *  @params : [int]   - [not null] - $code    
     *            [string]-  $message - 提示信息,若为空时,自动匹配业务层信息
     *            [array]  -[not null] - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  json
     */
    public static function json($code=0,$message='',array $data=[],$debug=[])
    {
        return json_encode(self::_body($code,$message,$data,$debug));
    }

    /*
     * 返回json数组资源(空 data 为json数组)
     * @params : [int] - ['not null] - $code
     *           [string] - $message 
     *           [array] - [null] - 空数组
     *           [array] - $debug
     *
     * @return json
     */
    public static function null_data_json($code=0,$message='',$debug=[])
    {
        $result = self::_body($code,$message,[],$debug);
        $result['data'] = [];
        return json_encode($result);
    }
    
    /* 返回xml数据资源
     *  @params : [int]    - $code    - code
     *            [string] - $message - 提示信息
     *            [array]  - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  xml
     */
    public static function action_xml($code=0,$message='',array $data=[],$debug=[])
    {
		return XMLHelper::arrayToXML(self::_body($code,$message,$data,$debug));
    }

    /*
     * 返回数组数据资源
     *  @params : [int]    - $code    - code
     *            [string] - $message - 提示信息
     *            [array]  - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  array
     */
    public static function action_array($code=0,$message='',array $data=[],$debug=[])
    {
		return self::_body($code,$message,$data,$debug);
    }

    /*
     * 封装数据body
     *  @params : [int]    - $code    - code
     *            [string] - $message - 提示信息
     *            [array]  - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  array
     */
    private static function _body($code=0,$message='',array $data=[],$debug=[])
    {
        if(empty($message))
        {
            $codeArray = Kohana::$config->load('common.RESULT_CODE');
            if(array_key_exists($code,$codeArray)) $message = $codeArray[$code];
        }
        if(empty($data)){
        	$data = new stdClass();
		}
        $arr = ['code'=>$code,'message'=>$message,'data'=>$data];
        if($debug) $arr['debug']=$debug;
        return $arr;
    }
}
