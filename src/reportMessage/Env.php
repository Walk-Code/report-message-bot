<?php

declare(strict_types=1);

/*
 * This file is part of the order-message package.
 */

namespace reportMessage;

/**
 * Class Env.
 */
class Env
{
    /**
     * env content.
     *
     * @var array
     */
    protected $data = [];

    /**
     * load env content.
     */
    public function load(string $file): void
    {
        $env = parse_ini_file($file, true) ?: [];
        $this->set($env);
    }

    /**
     * set env content.
     *
     * @param array $env
     * @param mixed        $value
     */
    public function set(array $env): void
    {
        $this->data = $env;
    }

    /**
     * get env content.
     *
     * @return array
     */
    public function get()
    {
        return $this->data;
    }
}
