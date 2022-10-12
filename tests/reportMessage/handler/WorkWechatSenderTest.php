<?php


namespace reportMessageTest\reportMessage\handler;


use reportMessage\handler\WorkWechatSender;
use reportMessageTest\reportMessage\BaseTestCase;

class WorkWechatSenderTest extends BaseTestCase
{
    private $workWechat;

    protected function setUp()
    {
        $this->workWechat = new WorkWechatSender();
    }

    protected function tearDown()
    {
        $this->workWechat = null;
    }

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

    public function test_set_config()
    {
        $config = [
            'work_wechat_bot' => [
                'bot_url' => 'http://example.com',
                'bot_key' => 'test',
            ],
        ];

        $obj = $this->workWechat->setConfig($config);
        $objConfig = $obj->config;
        $this->assertEquals('http://example.com', $objConfig['work_wechat_bot']['bot_url']);
        $this->assertEquals('test', $objConfig['work_wechat_bot']['bot_key']);
    }
}