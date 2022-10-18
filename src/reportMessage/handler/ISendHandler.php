<?php

declare(strict_types=1);

/*
 * This file is part of the report-message-bot package.
 */

namespace reportMessage\handler;

/**
 * send handle.
 */
interface ISendHandler
{
    /**
     * Desc: send data
     * Date: 2022/7/18
     * Time: 14:37.
     */
    public function send(array $data): bool;

    /**
     * Desc: set config
     * Date: 2022/7/18
     * Time: 14:37.
     *
     * @return void
     */
    public function setConfig(array $config): ISendHandler;
}
