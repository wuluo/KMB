<?php
return array(
	'passport' => 'gvcpassport',
	'lander' => 'database',
	'key' => ')&GY*^#<)(2O^#ME',
	'cipher' => MCRYPT_RIJNDAEL_128,
	'mode'   => MCRYPT_MODE_NOFB,
	
	'ldap' => array(
		'server' => '10.69.100.4',
		'port' => '389',
		'baseDN' => 'DC=ds,DC=gome,DC=com,DC=cn', //DC (Domain Component) CN (Common Name) OU (Organizational Unit)
		'bindRDN' => '',
		'password' => '',
		'suffix' => '@ds.gome.com.cn'
	),
		
	'database' => array(
		'group' => 'default',
	),
		
	'redis' => array(
		'server' => '127.0.0.1',
		'port' => '6379',
		'timeout' => 30,
		'persistent' => FALSE,
		'password' => 'foobared',
	),
);