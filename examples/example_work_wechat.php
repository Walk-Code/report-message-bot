<?php

use reportMessage\enum\LogLevelEnum;
use reportMessage\handle\WorkWechatSender;
use reportMessage\ReportMessage;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$level = LogLevelEnum::ERROR();
$test  = 'say something...';

$host  = '192.168.99.100';
$port  = '6310';
$auth  = 'root';
$redis = new \Redis();
$redis->connect($host, $port, 10);
$redis->auth($auth);
$redis->select(0);

$address = [
    'address' => [
        'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=2e8c48a4-2a70-4a44-b6f7-bf8aa2b35e8e',
    ],
];
ReportMessage::setRedis($redis);
ReportMessage::setConfig($address);
$result = ReportMessage::simpleLog($level, '', $test, 'multiple', 1, 5);
