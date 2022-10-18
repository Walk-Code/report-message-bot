<?php


namespace reportMessageTest\reportMessage\handler;

use GuzzleHttp\Exception\RequestException;
use reportMessage\handler\WorkWechatSender;
use reportMessageTest\reportMessage\BaseTestCase;

class WorkWechatSenderTest extends BaseTestCase
{
    private $workWechat;

    private $httpClient;

    protected function setUp()
    {
        $this->workWechat = new WorkWechatSender();
    }

    protected function tearDown()
    {
        $this->workWechat = null;
    }

    /**
     * @covers \reportMessage\handler\WorkWechatSender::formatMessage
     */
    public function test_format_message()
    {
        $title   = 'test';
        $uri     = 'test_uri';
        $traceId = 'test_trace_id';
        $content = 'test_content';
        $level   = 'test_level';
        $result  = $this->callMethod(
            $this->workWechat, 'formatMessage', [
                $title, $uri, $traceId, $content, $level
            ]
        );
        $this->assertIsArray($result);
        $this->assertEquals('markdown', $result['msgtype']);
        $this->assertIsArray($result['markdown']);
    }

    /**
     * @covers \reportMessage\handler\WorkWechatSender::setConfig
     */
    public function test_set_config()
    {
        $config = [
            'work_wechat_bot' => [
                'bot_url' => 'http://example.com',
                'bot_key' => 'test',
            ],
        ];

        $obj       = $this->workWechat->setConfig($config);
        $objConfig = $obj->config();
        $this->assertEquals('http://example.com', $objConfig['work_wechat_bot']['bot_url']);
        $this->assertEquals('test', $objConfig['work_wechat_bot']['bot_key']);
    }

    /**
     * @covers \reportMessage\handler\WorkWechatSender::config
     */
    public function test_config()
    {
        $config = $this->workWechat->config();
        $this->assertTrue(isset($config['work_wechat_bot']['bot_url']));
        $this->assertTrue(isset($config['work_wechat_bot']['bot_key']));
    }

    /**
     * @covers \reportMessage\handler\WorkWechatSender::send
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function test_send()
    {
        $this->expectException(RequestException::class);
        $result = $this->workWechat->send(['test']);
        // 单元测试允许失败
        $this->assertFalse($result);
    }
}