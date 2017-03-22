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
     *  @params : [int]    - $code    - code
     *            [string] - $message - 提示信息
     *            [array]  - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  json
     */
    public static function json($code,$message,$data,$debug)
    {
        echo json_encode(self::_body($code,$message,$data,$debug));
    }

    /* 返回xml数据资源
     *  @params : [int]    - $code    - code
     *            [string] - $message - 提示信息
     *            [array]  - $data    - 数据集合
     *            [array]  - $debug   - debug信息
     *
     *  @return:  xml
     */
    public static function action_xml($code,$message,$data,$debug)
    {
        echo XMLHelper::arrayToXML(self::_body($code,$message,$data,$debug));
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
    public static function action_array($code,$message,$data,$debug)
    {
        var_dump(self::_body($code,$message,$data,$debug));
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
    private static function _body($code,$message,$data,$debug)
    {
        $arr = ['code'=>$code,'message'=>$message,'data'=>$data];
        if($debug) $arr['debug']=$debug;
        return $arr;
    }
}
