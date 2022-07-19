<?php

declare(strict_types=1);

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

    public function __construct()
    {
        $this->data = $_ENV;
    }

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
     * @param string|array $env
     * @param mixed        $value
     */
    public function set($env): void
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
