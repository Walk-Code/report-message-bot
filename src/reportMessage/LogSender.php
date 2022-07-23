<?php

declare(strict_types=1);

namespace reportMessage;

use reportMessage\handle\SendHandle;

class LogSender
{
    /**
     * cache prefix.
     *
     * @var string
     */
    protected $cachePrefix = 'reportMessage';

    /**
     * cache log prefix.
     *
     * @var string
     */
    protected $lockKeyPrefix = 'reportMessage_lock';

    /**
     * default frequency.
     *
     * @var int
     */
    protected $frequency = 5;

    /**
     * The default time interval unit is second. (The default is to notify if there are more than 5 alarms within 5 minutes).
     *
     * @var int
     */
    protected $expire = 60 * 5;

    /**
     * @var \Redis
     */
    protected $redis;

    /**
     * cache key.
     *
     * @var string
     */
    protected $cacheKey = '';

    /**
     * lock key.
     *
     * @var string
     */
    protected $lockKey = '';

    /**
     * @var SendHandle
     */
    protected $handler;

    /**
     * LogSender constructor.
     */
    public function __construct(SendHandle $sendHandle, \Redis $redis)
    {
        $this->setHandler($sendHandle);
        $this->redis = $redis;
    }

    /**
     * Desc: send handler config
     * Date: 2022/7/18
     * Time: 15:01.
     *
     * @return $this
     */
    public function setHandlerConfig(array $config): LogSender
    {
        $this->handler->setConfig($config);

        return $this;
    }

    /**
     * Desc: set frequency
     * Date: 2022/7/18
     * Time: 15:01.
     *
     * @return $this
     */
    public function setFrequency(int $times): LogSender
    {
        $this->frequency = $times;

        return $this;
    }

    /**
     * Desc: get frequency.
     * Date: 2022/7/18
     * Time: 15:02.
     */
    public function getFrequency(): int
    {
        return $this->frequency;
    }

    /**
     * Desc: set expire time.
     * Date: 2022/7/18
     * Time: 15:02.
     *
     * @return $this
     */
    public function setExpire(int $time): LogSender
    {
        $this->expire = $time;

        return $this;
    }

    /**
     * Desc: get expire time.
     * Date: 2022/7/18
     * Time: 15:02.
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * Desc: set send handler.
     * Date: 2022/7/18
     * Time: 15:02.
     *
     * @param $handler
     *
     * @return $this
     */
    public function setHandler($handler): LogSender
    {
        $this->handler         = $handler;
        $this->handler->sender = $this;

        return $this;
    }

    /**
     * Desc: set cache key.
     * Date: 2022/7/18
     * Time: 15:02.
     *
     * @return $this
     */
    public function setCacheKey(string $key): LogSender
    {
        $this->cacheKey = $this->cachePrefix . '_' . $key;
        $this->lockKey  = $this->lockKeyPrefix . '_' . $key;

        return $this;
    }

    /**
     * Desc: check frequency.
     * Date: 2022/7/18
     * Time: 15:04.
     */
    public function checkFrequency(): bool
    {
        $key  = $this->cacheKey;
        $isOk = $this->redis->set($key, 1, ['nx', 'ex' => $this->expire]);
        if (false === $isOk) {
            $times = $this->redis->incrby($key, 1);
        } else {
            $times = 1;
        }
        if ($times >= $this->frequency) {
            return true;
        }

        return false;
    }

    /**
     * Desc: lock
     * Date: 2022/7/18
     * Time: 15:04.
     *
     * @return bool
     */
    public function lock()
    {
        $ttl = $this->redis->ttl($this->cacheKey);

        $ttl = $ttl <= 0 ? 1 : $ttl;

        return $this->redis->set($this->lockKey, 1, ['nx', 'ex' => $ttl]);
    }

    /**
     * Desc: send data
     * Date: 2022/7/18
     * Time: 15:05.
     *
     * @throws \Exception
     */
    public function send(array $data): bool
    {
        if (is_null($this->handler)) {
            throw new \Exception('sender not defined');
        }

        if ($this->checkFrequency() && $this->lock()) {
            return $this->handler->send($data);
        }

        return false;
    }
}
