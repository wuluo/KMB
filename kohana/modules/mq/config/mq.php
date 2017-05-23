<?php
/**
 * mq config
 * @author: panchao
 */
return [

	'wmq' =>  [

		/**
		 * 转码
		 */
		'convert.accept' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/convert.accept.ssp',
			'token' => 'UISeiClDEbNyxmLU6eHHQIAyI3KkNMQt',
		],

		/**
		 * 直播 gls
		 */
		'gls.accept' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/gls.accept.ssp',
			'token' => 'U8rQ8Kf1fC3NJoNmbo2lsKly81f4ApXl',
		],

		/**
		 * 直播 channel
		 */
		'gls.channel' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/gls.channel.ssp',
			'token' => 'IYBm9HYnjBTpPukGeCQvtdx4I3Y2y2lh',
		],

		/**
		 * 机器上报
		 */
		'machine' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/machine.ssp',
			'token' => 'UISeiClDEbNyxmLU6eHHQIAyI3KkNMQt',
		],

		/**
		 * 直播节目
		 */
		'program' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/program.ssp',
			'token' => 'iHcLpmcmMREFOTKIWpTSXGP34O3VqMsD',
		],

		/**
		 * 直播录制
		 */
		'record' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/record.ssp',
			'token' => '9S3LZQSoQm7hjFgwVMDG8coRhqVYNx3b',
		],

		/**
		 * 直播 show
		 */
		'show' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/show.ssp',
			'token' => 'DtpiXXFgtFi0CTvE3WcMgqmSJ2lIcns2',
		],

		/**
		 * 直播 show stream流
		 */
		'showStream' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/showStream.ssp',
			'token' => 'R8mgNJJnzTnNmC3ZiqeODg1nvcz3fAD3',
		],

		/**
		 * sphinx
		 */
		'sphinx' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/sphinx.ssp',
			'token' => 'xYnQwaPTRDZvbK67BMNmmPRHzYLLU870',
		],

		/**
		 * 视频
		 */
		'video' => [
			'url' => 'http://wmq.v.dev.gomeplus.com:21122/Mq/video.ssp',
			'token' => 'L6CR2hvynWX0JaHh9yUbPE5gGj70FP9x',
		]
	]
];