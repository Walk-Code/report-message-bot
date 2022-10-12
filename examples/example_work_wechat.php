<?php

use reportMessage\entity\Message;
use reportMessage\enum\LogLevelEnum;
use reportMessage\ReportMessage;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$host  = '10.8.8.83';
$port  = '6379';
$auth  = 'root';
$redis = new \Redis();
$redis->connect($host, $port, 10);
$redis->auth($auth);
$redis->select(0);

$address = [
    'address' => [
        //'企业机器人地址',
        'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=2e8c48a4-2a70-4a44-b6f7-bf8aa2b35e8e',
    ]
];
//$message = new Message($level, mt_rand(10000, 99999), $test, 'multiple', 2, 5 * 60);
//ReportMessage::getInstance()->setRedis($redis)
//    ->setConfig($address)
//    ->simpleLog($message);
$result = ReportMessage::getInstance()
    ->setRedis($redis)
    ->setFrequency('r:l:i', 10000, 5)
    ->sendMessage((new \reportMessage\handler\WorkWechatSender()), [
        'title' => 'test',
        'uri' => 'http://example.com',
        'traceId' => uniqid(),
        'content' => 'say something...'.mt_rand(100000, 999999),
        'level' => LogLevelEnum::INFO,
    ]);
