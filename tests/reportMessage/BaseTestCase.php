<?php


namespace reportMessageTest\reportMessage;


use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected static function setProperty($object, $propertyName, $propertyValue)
    {
        $reflection          = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($propertyName);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $propertyValue);
    }

    protected static function getProperty($object, $propertyName)
    {
        $reflection          = new \ReflectionClass($object);
        $reflection_property = $reflection->getProperty($propertyName);
        $reflection_property->setAccessible(true);
        return $reflection_property->getValue($object);
    }

    protected static function callMethod($object, $methodName, $arguments = [])
    {
        $reflection        = new \ReflectionClass($object);
        $reflection_method = $reflection->getMethod($methodName);
        $reflection_method->setAccessible(true);
        return $reflection_method->invokeArgs($object, $arguments);
    }
}