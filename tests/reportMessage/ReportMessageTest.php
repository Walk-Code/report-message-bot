<?php
namespace reportMessageTest\reportMessage;

use reportMessage\ReportMessage;
use reportMessageTest\reportMessage\BaseTestCase;

class ReportMessageTest extends BaseTestCase
{
    public function tset_get_instance()
    {
        $obj = ReportMessage::getInstance();
        $this->assertInstanceOf(ReportMessage::class, $obj);
    }

    public function test_set_redis()
    {
        $redis = new \Redis();
        $obj = ReportMessage::getInstance()->setRedis($redis);
        $this->assertInstanceOf(ReportMessage::class, $obj);
    }
}
