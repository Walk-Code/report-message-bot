<?php


namespace reportMessageTest\reportMessage;


use reportMessage\Config;

class ConfigTest extends BaseTestCase
{
    /**
     * @covers \reportMessage\Config::getData
     */
    public function test_get_data()
    {
        $configPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'.env';
        $config = (new Config())->getData($configPath);
        $this->assertEquals('1234', $config['DATABASE']);
        $this->assertEquals('123', $config['PORT']);
        $this->assertEquals('abc', $config['report-message']['aa']);
        $this->assertEquals('zzz', $config['report-message']['name']);
    }

    /**
     * @covers \reportMessage\Config::getData
     */
    public function test_get_data_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('configuration file read exception!');
        (new Config())->getData('/test');
    }
}