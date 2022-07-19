<?php
use reportMessage\enum\LogLevelEnum;
use reportMessage\ReportMessage;

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$level = LogLevelEnum::ERROR();
$key = '111';
$traceId = '12345';
$content = '测试日志';
$frequency = 12;
$duration = 60 * 60;

$host = '192.168.99.100';
$port = '6310';
$auth = 'hangqun';
$redis = new \Redis();
$redis->connect($host, $port, 10);
$redis->auth($auth);
$redis->select(0);

ReportMessage::setRedis($redis);
ReportMessage::$configFile = __DIR__.DIRECTORY_SEPARATOR.'.env';
ReportMessage::log($level, $key, $traceId, $content, $frequency, $duration);