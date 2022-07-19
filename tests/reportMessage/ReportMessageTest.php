<?php

use PHPUnit\Framework\TestCase;
use reportMessage\enum\LogLevelEnum;
use reportMessage\ReportMessage;

class ReportMessageTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @throws Throwable
     */
    public function testLogException($level, $key, $traceId, $content, $frequency, $duration)
    {
        $this->expectException(InvalidArgumentException::class);
        ReportMessage::log($level, $key, $traceId, $content, $frequency, $duration);
    }

    /**
     * @dataProvider additionProviderByError
     * @throws Throwable
     */
    public function testHandleException($level, $key, $traceId, $content, $frequency, $duration)
    {
        ReportMessage::setErrHandler(function ($e) {
            $this->assertInstanceOf(Exception::class, $e);
        });
        $this->assertFalse(ReportMessage::log($level, $key, $traceId, $content, $frequency, $duration));
    }

    public function testConfig()
    {
        ReportMessage::$configFile = __DIR__ . DIRECTORY_SEPARATOR . '.env';
        $config                    = ReportMessage::config();
        $this->assertIsArray($config);
        $this->assertSame($config['aa'], 'abc');
    }

    public function additionProvider(): array
    {
        $level     = LogLevelEnum::ERROR();
        $key       = '111';
        $traceId   = '123456';
        $text      = '测试日志';
        $frequency = 12;
        $duration  = 60;

        return [
            [$level, $key, $traceId, $text, $frequency, $duration],
        ];
    }

    public function additionProviderByError(): array
    {
        $level     = LogLevelEnum::ERROR();
        $key       = '111';
        $traceId   = '123456';
        $text      = '测试日志';
        $frequency = 12;
        $duration  = 60;

        return [
            [$level, $key, $traceId, $text, 0, $duration],
            [$level, $key, $traceId, $text, $frequency, 0],
        ];
    }
}
