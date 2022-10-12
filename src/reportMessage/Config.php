<?php

declare(strict_types=1);

/*
 * This file is part of the order-message package.
 */

namespace reportMessage;

class Config
{
    /**
     * return config content.
     */
    public function getData(): array
    {
        $configFile = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env';
        if (!is_file($configFile)) {
            throw new \InvalidArgumentException('configuration file read exception!');
        }
        $env = new Env();
        $env->load($configFile);

        return $env->get();
    }
}
