<?php

declare(strict_types=1);

/*
 * This file is part of the order-message package.
 */

namespace reportMessage\enum;

use MyCLabs\Enum\Enum;

/**
 * Define the log level.
 *
 * Class LogLevelEnum
 *
 * @method static LogLevelEnum ERROR()
 * @method static LogLevelEnum WARNING()
 * @method static LogLevelEnum NOTICE()
 * @method static LogLevelEnum INFO()
 */
class LogLevelEnum extends Enum
{
    public const ERROR   = 'error';
    public const WARNING = 'warning';
    public const NOTICE  = 'notice';
    public const INFO    = 'info';
}
