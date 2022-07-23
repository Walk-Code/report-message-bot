<?php

declare(strict_types=1);

namespace reportMessage;

use reportMessage\entity\Message;
use reportMessage\enum\LogLevelEnum;
use reportMessage\handle\SendHandle;
use reportMessage\handle\WorkWechatSender;

class ReportMessage
{
    /**
     * default env file.
     *L.
     *
     * @var string
     */
    public static $configFile = '';

    /**
     * send handle.
     *
     * @var SendHandle
     */
    protected static $handle;
    /**
     * custom error handler.
     *
     * @var callable|null
     */
    private $errHanler;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * config message.
     *
     * @var array
     */
    private static $config = [];

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
     * Desc: Send notification content
     * Date: 2022/7/18
     * Time: 15:09.
     */
    public function send(LogLevelEnum $level, string $text, string $key = '', string $traceId = '', int $frequency = 0, int $duration = 0): bool
    {
        if (empty($key)) {
            $key = 'default';
        }

        return self::log($level, $key, $traceId, $text, $frequency, $duration);
    }

    /**
     * Desc: log
     * Date: 2022/7/18
     * Time: 15:09.
     *
     * @throws \Throwable
     */
    public function log(LogLevelEnum $level, string $key, string $traceId, string $text, int $frequency = 0, int $duration = 0): bool
    {
        try {
            $data = [
                'level'   => $level->getValue(),
                'uri'     => $_SERVER['REQUEST_URI'] ?? '',
                'traceId' => $traceId,
                'text'    => $text,
            ];

            $config = $this->config();
            $handle = self::switchHandle($config);
            $redis  = $this->redis;
            if (empty($redis)) {
                throw new \InvalidArgumentException('redis config error!');
            }

            $oLogSender = new LogSender($handle, $redis);
            $oLogSender->setHandlerConfig($config);

            if ($frequency > 0) {
                $oLogSender->setFrequency($frequency);
            }
            if ($duration > 0) {
                $oLogSender->setExpire($duration);
            }

            return $oLogSender->setCacheKey($key)->send($data);
        } catch (\Throwable $e) {
            static::dealError($e);

            return false;
        }
    }

    /**
     * Desc: Simple alert
     * Date: 2022/7/18
     * Time: 15:10.
     * string.
     *
     * @return bool
     */
    public function simpleLog(Message $message)
    {
        $data = [
            'title' => $message->getTitle(),
            'uri'   => $message->getUri(),
            'text'  => str_replace('\\', '\\\\', $message->getContent()),
        ];
        if (empty($this->redis)) {
            throw new \InvalidArgumentException('redis config error!');
        }
        $oLogSender = new LogSender(new WorkWechatSender(self::config()), $this->redis);
        $frequency  = $message->getFrequency();
        $duration   = $message->getDuration();
        if ($frequency > 0) {
            $oLogSender->setFrequency($frequency);
        }
        if ($duration > 0) {
            $oLogSender->setExpire($duration);
        }

        return $oLogSender->setCacheKey($message->getKey())->send($data);
    }

    /**
     * Desc: set redis.
     * Date: 2022/7/18
     * Time: 15:15.
     */
    public function setRedis(\Redis $redis): ReportMessage
    {
        if (!$this->redis) {
            $this->redis = $redis;
        }

        return $this;
    }

    /**
     * Desc: switch handle.
     * Date: 2022/7/18
     * Time: 15:15.
     */
    public static function switchHandle(array $config): SendHandle
    {
        if (!self::$handle) {
            $handle       = $config['handler'] ?? WorkWechatSender::class;
            self::$handle = new $handle();
        }

        return self::$handle;
    }

    /**
     * Desc: set config.
     * Date: 2022/7/18
     * Time: 15:15.
     */
    public function setConfig(array $config): ReportMessage
    {
        self::$config = $config;

        return $this;
    }

    /**
     * Desc: get config.
     * Date: 2022/7/18
     * Time: 15:20.
     */
    public function config(): array
    {
        if (empty(self::$config)) {
            if (empty(self::$configFile)) {
                self::$configFile = __DIR__ . '/../../../../.env';
            }
            if (!is_file(self::$configFile)) {
                throw new \InvalidArgumentException('configuration file read exception!');
            }
            $env = new Env();
            $env->load(self::$configFile);
            $config       = $env->get();
            self::$config = $config['report-message'] ?? [];
        }

        return self::$config;
    }

    /**
     * set error handler.
     */
    public function setErrHandler(?callable $callable): void
    {
        $this->errHanler = $callable;
    }

    /**
     * Desc: deal Error
     * Date: 2022/7/18
     * Time: 15:21.
     *
     * @throws \Throwable
     */
    private function dealError(\Throwable $e): void
    {
        if (is_callable($this->errHanler)) {
            call_user_func($this->errHanler, $e);
        } else {
            throw $e;
        }
    }
}
