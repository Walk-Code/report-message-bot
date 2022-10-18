<?php

declare(strict_types=1);

/*
 * This file is part of the report-message-bot package.
 */

namespace reportMessage;

class Config
{
    /**
     * return config content.
     * @param  string $path
     * @return array
     */
    public function getData(string $path = ''): array
    {
        if (empty($path)) {
            $configFile = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.env';
        } else {
            $configFile = $path;
        }

        if (!is_file($configFile)) {
            throw new \InvalidArgumentException('configuration file read exception!');
        }
        $env = new Env();
        $env->load($configFile);

        return $env->get();
    }
}
