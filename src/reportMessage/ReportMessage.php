<?php

declare(strict_types=1);

/*
 * This file is part of the report-message-bot package.
 */

namespace reportMessage;

use reportMessage\exceptions\InvaildRedisConfig;
use reportMessage\handler\ISendHandler;

class ReportMessage
{
    /**
     * Turn on frequency control.
     *
     * @var bool
     */
    private $isUseRate = false;

    /**
     * Intercept send messages lock.
     *
     * @var bool
     */
    private $isLock;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * @var object
     */
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * set Redis drive.
     */
    public function setRedis(\Redis $redis): self
    {
        $this->redis = $redis;

        return $this;
    }

    /**
     * send message.
     */
    public function sendMessage(ISendHandler $sendHandle, array $data): bool
    {
        $sendHandle->setConfig((new Config())->getData());

        if ($this->isUseRate && !$this->isLock) {
            return $sendHandle->send($data);
        }

        return $sendHandle->send($data);
    }

    /**
     * set rate config.
     *
     * @param $key
     * @param $frequency
     * @param $duration
     *
     * @throws InvaildRedisConfig
     *
     * @return $this
     */
    public function setFrequency($key, $frequency, $duration): self
    {
        $this->isUseRate = true;
        $maxCallsPerHour = $frequency; // 每小时最大调用数
        $slidingWindow   = $duration; // 滑窗大小
        $now             = microtime(true);
        $redis           = $this->redis;

        if (is_null($this->redis)) {
            throw new InvaildRedisConfig('Invalid redis config!');
        }

        if (count($redis->zRange($key, 0, -1)) >= $maxCallsPerHour) {
            $this->isLock = false;

            return $this;
        }

        $redis->multi();
        $redis->zRangeByScore($key, (string) 0, (string) ($now - $slidingWindow));
        $redis->zRange($key, 0, -1);
        $redis->zAdd($key, $now, $now);
        $redis->expire($key, $slidingWindow);
        $result = $redis->exec();
        $redis->close();
        $timeStamps   = $result[1];
        $remaining    = max(0, $maxCallsPerHour - count($timeStamps));
        $this->isLock = $remaining > 0;

        return $this;
    }
}
