<?php

use PHPUnit\Framework\TestCase;
use reportMessage\Env;

class EnvTest extends TestCase
{
    protected $env;

    protected function setUp()
    {
        $this->env = new Env();
        $file = __DIR__.DIRECTORY_SEPARATOR.'.env';
        $this->env->load($file);
    }

    public function testLoad() {
        $env = $this->env;
        $this->assertSame('1234', $env->get()['DATABASE']);
        $this->assertSame('123', $env->get()['PORT']);
        $this->assertIsString($env->get()['PORT']);
    }

    public function testGet() {
        $env = $this->env;
        $get = $env->get();
        $this->assertIsArray($get);
        $this->assertTrue(count($get) > 2);
        $this->assertTrue(isset($get['report-message']));
        $this->assertIsArray($get['report-message']);
        $this->assertSame($get['report-message']['name'], 'zzz');
    }
}