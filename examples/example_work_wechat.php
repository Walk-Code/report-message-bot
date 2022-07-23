<?php

use reportMessage\entity\Message;
use reportMessage\enum\LogLevelEnum;
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
        '企业机器人地址',
    ]
];
$message = new Message($level, '1', $test, 'multiple', 1, 5);
ReportMessage::getInstance()->setRedis($redis)
    ->setConfig($address)
    ->simpleLog($message);
