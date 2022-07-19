<?php

declare(strict_types=1);

namespace reportMessage;

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
    private static $errHanler;

    /**
     * @var \Redis
     */
    private static $redis;

    /**
     * config message.
     *
     * @var array
     */
    private static $config = [];

    /**
     * Desc: Send notification content
     * Date: 2022/7/18
     * Time: 15:09.
     */
    public static function send(LogLevelEnum $level, string $text, string $key = '', string $traceId = '', int $frequency = 0, int $duration = 0): bool
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
    public static function log(LogLevelEnum $level, string $key, string $traceId, string $text, int $frequency = 0, int $duration = 0): bool
    {
        try {
            $data = [
                'level'   => $level->getValue(),
                'uri'     => $_SERVER['REQUEST_URI'] ?? '',
                'traceId' => $traceId,
                'text'    => $text,
            ];

            $config = self::config();
            $handle = self::switchHandle($config);
            $redis  = self::$redis;
            var_dump($data);
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
     *
     * @return bool
     */
    public static function simpleLog(string $title, string $uri, string $msg, string $key, int $frequency = 0, int $duration = 0)
    {
        try {
            $data = [
                'title' => $title,
                'uri'   => $uri,
                'text'  => str_replace('\\', '\\\\', $msg),
            ];
            if (empty(self::$redis)) {
                throw new \InvalidArgumentException('redis配置异常');
            }
            $oLogSender = new LogSender(new WorkWechatSender(self::config()), self::$redis);
            if ($frequency > 0) {
                $oLogSender->setFrequency($frequency);
            }
            if ($duration > 0) {
                $oLogSender->setExpire($duration);
            }

            return $oLogSender->setCacheKey($key)->send($data);
        } catch (\Throwable $e) {
            //return false;
        }
    }

    /**
     * Desc: set redis.
     * Date: 2022/7/18
     * Time: 15:15.
     */
    public static function setRedis(\Redis $redis): \Redis
    {
        if (!self::$redis) {
            self::$redis = $redis;
        }

        return self::$redis;
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
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Desc: get config.
     * Date: 2022/7/18
     * Time: 15:20.
     */
    public static function config(): array
    {
        if (empty(self::$config)) {
            if (empty(self::$configFile)) {
                self::$configFile = __DIR__ . '/../../../../.env';
            }
            if (!is_file(self::$configFile)) {
                throw new \InvalidArgumentException('配置文件读取异常');
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
    public static function setErrHandler(?callable $callable): void
    {
        static::$errHanler = $callable;
    }

    /**
     * Desc: deal Error
     * Date: 2022/7/18
     * Time: 15:21.
     *
     * @throws \Throwable
     */
    private static function dealError(\Throwable $e): void
    {
        if (is_callable(static::$errHanler)) {
            call_user_func(static::$errHanler, $e);
        } else {
            throw $e;
        }
    }
}
