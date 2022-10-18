<?php

namespace reportMessageTest\reportMessage;

use reportMessage\exceptions\InvaildRedisConfig;
use reportMessage\handler\ISendHandler;
use reportMessage\ReportMessage;

class ReportMessageTest extends BaseTestCase
{
    private static $redis;

    private $sender;

    public static function setUpBeforeClass()
    {
        $host  = 'redis';
        $port  = '6379';
        $auth  = 'root';
        $redis = new \Redis();
        $redis->connect($host, $port, 10);
        $redis->auth($auth);
        $redis->select(0);
        self::$redis = $redis;
    }

    protected function setUp()
    {
        $this->sender = (new class() implements ISendHandler {

            public function send(array $data): bool
            {
                return true;
            }

            public function setConfig(array $config): ISendHandler
            {
                return $this;
            }
        });
    }

    /**
     * @covers \reportMessage\ReportMessage::getInstance
     */
    public function test_get_instance()
    {
        $obj = ReportMessage::getInstance();
        $this->assertInstanceOf(ReportMessage::class, $obj);
    }

    /**
     * @covers \reportMessage\ReportMessage::setRedis
     */
    public function test_set_redis()
    {
        $redis = new \Redis();
        $obj   = ReportMessage::getInstance()->setRedis($redis);
        $this->assertInstanceOf(ReportMessage::class, $obj);
    }

    /**
     * @covers \reportMessage\ReportMessage::__clone
     */
    public function test_clone()
    {
        $result = self::callMethod(ReportMessage::getInstance(), '__clone');
        $this->assertNotSame($result, ReportMessage::getInstance());
    }

    /**
     * @covers \reportMessage\ReportMessage::__construct
     */
    public function test_construct()
    {
        $result = self::callMethod(ReportMessage::getInstance(), '__construct');
        $this->assertNotSame($result, ReportMessage::getInstance());
    }

    /**
     * @covers \reportMessage\ReportMessage::setFrequency
     * @throws InvaildRedisConfig
     */
    public function test_set_frequency_exception()
    {
        $result = ReportMessage::getInstance()->setRedis(self::$redis)->setFrequency('test', 1, 5);
        $this->assertInstanceOf(ReportMessage::class, $result);
    }

    public function test_send()
    {
        $result = ReportMessage::getInstance()->sendMessage($this->sender, []);
        $this->assertTrue($result);
    }
}
