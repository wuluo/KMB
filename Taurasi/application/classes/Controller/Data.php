<?php
/*
* +----------------------------------------------------------------------+
* | Copyright (c) 美信 - 信息技术中心
* +----------------------------------------------------------------------+
* | All rights reserved.
* +----------------------------------------------------------------------+
* | @程序名称：Data.php
* +----------------------------------------------------------------------+
* | @程序功能：
* +----------------------------------------------------------------------+
* | Author:wujing01 <wujing01@gomeplus.com>
* +----------------------------------------------------------------------+
* | Date: 2017/2/17 14:18
* +----------------------------------------------------------------------+
*/

Class Controller_Data extends Controller
{
	public function action_comment_list()
	{
		$data = '{
			"code": 200,
			"msg": "success",
			"data": {
				"app_id": 123,
				"source_id": 345,
				"cursor": 1145342424,
				"list": [
					{
						"comment_uuid": "42fdsfas24245",
						"content": "假的",
						"user": {
							"user_id": 41244,
							"user_name": "323213",
							"avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
						},
						"reply": [
							{
								"reply_id": "31fwwe13",
								"content": "还是假的",
								"from_user": {
									"user_id": 41244,
									"user_name": "323213",
									"avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
								},
								"to_user": {
									"user_id": 13134,
									"user_name": "ffwe",
									"avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
								}
							}
						]
					}
				]
			}
		}';
		echo $data;exit;
	}

	public function action_comment()
	{
		$data = '{
			"code": 200,
			"msg": "success",
			"data": {
				"app_id": 123,
				"source_id": 345,
				"comment_uuid": 114534,
				"content": "12131313" ,
				"user":{
					"user_id": 13134,
					"user_name": "ffwe",
					"avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
				}
			}
		}';
		echo $data;exit;
	}

	public function action_reply()
	{
		$data = '{
			"code": 200,
			"msg": "success",
			"data": {
				"app_id": 123,
				"source_id": 345,
				"comment_uuid": 114534,
				"content": "12131313" ,
				"user":{
					"user_id": 13134,
					"user_name": "ffwe",
					"avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
				},
				"reply":{
					"reply_id": "31fwwe13",
					"content": "还是假的",
					"from_user": {
						  "user_id": 41244,
						  "user_name": "323213",
						  "avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
					 },
					 "to_user": {
						  "user_id": 13134,
						  "user_name": "ffwe",
						  "avatar": "https://i5.meixincdn.com/v1/img/T17tCTB5Ls1RXrhCrK.jpg"
					 }
				}
			}
		}';
		echo $data;exit;
	}
}
 
 