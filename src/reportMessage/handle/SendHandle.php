<?php

namespace reportMessage\handle;

/**
 * send handle
 */
interface SendHandle
{
    /**
     * Desc: send data
     * Date: 2022/7/18
     * Time: 14:37
     * @param array $data
     * @return bool
     */
    public function send(array $data): bool;


    /**
     * Desc: set config
     * Date: 2022/7/18
     * Time: 14:37
     * @param array $config
     * @return void
     */
    public function setConfig(array $config): SendHandle;
}