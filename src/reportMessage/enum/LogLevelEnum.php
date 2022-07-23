<?php

namespace reportMessage\enum;

use MyCLabs\Enum\Enum;

/**
 * Define the log level.
 *
 * Class LogLevelEnum
 *
 * @method static LogLevelEnum   ()
 * @method static LogLevelEnum WARNING()
 * @method static LogLevelEnum NOTICE()
 * @method static LogLevelEnum INFO()
 */
class LogLevelEnum extends Enum
{
    const ERROR   = 'error';
    const WARNING = 'warning';
    const NOTICE  = 'notice';
    const INFO    = 'info';
}
