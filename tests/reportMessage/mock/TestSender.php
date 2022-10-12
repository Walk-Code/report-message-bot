<?php


namespace reportMessageTest\reportMessage\mock;


use reportMessage\handler\ISendHandler;

class TestSender implements ISendHandler
{

    public function send(array $data): bool
    {
        return true;
    }

    public function setConfig(array $config): ISendHandler
    {
        return  $this;
    }
}