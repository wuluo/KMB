<?php

/*
* +----------------------------------------------------------------------+
* | Copyright (c) 美信 - 信息技术中心
* +----------------------------------------------------------------------+
* | All rights reserved.
* +----------------------------------------------------------------------+
* | @程序名称：Comment.php
* +----------------------------------------------------------------------+
* | @程序功能：
* +----------------------------------------------------------------------+
* | Author:wujing01 <wujing01@gomeplus.com>
* +----------------------------------------------------------------------+
* | Date: 2017/2/17 14:38
* +----------------------------------------------------------------------+
*/

Class Controller_Api_Comment extends Controller_Api_Base
{
	public function before()
	{
		parent::before();
		$commentDomain = Kohana::$config->load('default.http.interface_app_comment');
		$this->_comment_list =$commentDomain.'v1/comment_list';
		$this->_comment_post = $commentDomain.'v1/comment';
		$this->_comment_reply = $commentDomain.'v1/comment_reply';
		/*$this->_comment_list = $commentDomain . 'data/comment_list';
		$this->_comment_post = $commentDomain . 'data/comment?app_id=1';
		$this->_comment_reply = $commentDomain . 'data/reply?app_id=1';*/
	}

	/**
	 * @param
	 * @author wujing01
	 */
	public function action_post()
	{
		$validate = Validation::factory($this->request->post())->filter(TRUE, 'trim');
		$rules = array(
			'user_id'=>array(
				'not_empty' =>NULL,
				'numeric'=>NULL,
			),
			'topic_id'=>array(
				'not_empty'=>NULL,
			),
			'content'=>array(
				'not_empty'=>NULL,
				'max_length'=>array(300),
			),
		);
		$validate
			->rule('user_id', $rules['user_id'])
			->rule('topic_id', $rules['topic_id'])
			->rule('content', $rules['content']);
		if ($validate->check()) {
			$param = $this->request->post();
			$comment = $this->_curlrequest('post', $this->_comment_post, $param);
			if (!$this->_check_exception($comment['body'])) {
				$data = $comment['body']['data'];
			}
			Curlresponse::json($comment['status'], $comment['body']['msg'], $data, '');
		} else {
			$errors = $validate->errors();
			Curlresponse::json('200', '评论内容不能为空', array(), '');
		}

	}

	public function action_reply()
	{
		$validate = Validation::factory($this->request->post())->filter(TRUE, 'trim');
		$validate
			->rule('user_id', 'numeric')
			->rule('topic_id', 'not_empty')
			->rule('comment_uuid', 'numeric')
			->rule('reply_uuid', 'numeric')
			->rule('content', 'not_empty');
		if ($validate->check()) {
			$param = $this->request->post();
			$comment = $this->_curlrequest('post', $this->_comment_reply, $param);
			if (!$this->_check_exception($comment['body'])) {
				$data = $comment['body']['data'];
			}
			Curlresponse::json($comment['status'], $comment['body']['msg'], $data, '');
		} else {
			$errors = $validate->errors();
			Curlresponse::json('200', '回复内容不能为空', array(), '');
		}
	}

	public function action_list()
	{
		$validate = Validation::factory($this->request->query())->filter(TRUE, 'trim');
		$validate->rule('topic_id', 'not_empty');
		if ($validate->check()) {
			$param = array_merge($this->request->query(), array('user_id' => 1));
			$comment = $this->_curlrequest('post', $this->_comment_list, $param);
			//var_dump($comment);exit;
			if (!$this->_check_exception($comment['body'])) {
				$data = $comment['body']['data'];
			}
			Curlresponse::json($comment['status'], $comment['body']['msg'], $data, '');
		} else {
			$errors = $validate->errors();
			Curlresponse::json('200', '资源ID不能为空', array(), '');
		}
	}
}
 