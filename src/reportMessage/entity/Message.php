<?php

namespace reportMessage\entity;

class Message
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $frequency;

    /**
     * @var int
     */
    private $duration;

    public function __construct(string $title, string $uri, string $content, string $key, int $frequency = 0, int $duration = 0)
    {
        $this->title     = $title;
        $this->uri       = $uri;
        $this->content   = $content;
        $this->key       = $key;
        $this->frequency = $frequency;
        $this->duration  = $duration;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function setFrequency(int $frequency): void
    {
        $this->frequency = $frequency;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getFrequency(): int
    {
        return $this->frequency;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
