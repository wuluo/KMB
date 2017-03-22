<?php
return array(
	'type' => 'database',
	
	'database' => array(
		'group' => 'account',
		'table' => 'gvs_log',
		'columns' => array(
			'portal',
			'controller',
			'action',
			'get',
			'post',
			'message',
			'ip',
			'user_agent',
			'referer',
			'account_id',
			'account_name',
			'create_time',
		),
	),
);