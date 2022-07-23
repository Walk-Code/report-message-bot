<?php

namespace reportMessage\handle;

use GuzzleHttp\Client;
use reportMessage\LogSender;

/**
 * Class WorkWechatSender.
 *
 * @see https://developer.work.weixin.qq.com/document/path/91770#%E6%B6%88%E6%81%AF%E5%8F%91%E9%80%81%E9%A2%91%E7%8E%87%E9%99%90%E5%88%B6
 */
class WorkWechatSender implements SendHandle
{
    /**
     * Log sender.
     *
     * @var LogSender
     */
    public $sender;

    /**
     * config.
     *
     * @var array
     */
    public $config = [
        'address' => '',
    ];

    public function __construct($config = [])
    {
        if (!empty($this->config)) {
            $this->config = $config;
        }
    }

    /**
     * Desc: send data
     * Date: 2022/7/18
     * Time: 14:49.
     */
    public function send(array $data): bool
    {
        $text = $this->formatMessage($data['title'] ?? '', $data['uri'] ?? '', $data['traceId'] ?? '', $data['text'] ?? '', $data['level'] ?? 'warning');

        $client = new Client();

        if (is_array($this->config['address'])) {
            foreach ($this->config['address'] as $address) {
                $response = $client->request('POST', $address, ['json' => $text, 'verify' => false]);
                $response->getStatusCode();
            }
            return true;
        }

        $response = $client->request('POST', $this->config['address'], ['json' => $text, 'verify' => false]);
        $status   = $response->getStatusCode();
        if (200 == $status) {
            return true;
        }

        return false;
    }

    /**
     * Desc: set send config.
     * Date: 2022/7/18
     * Time: 14:48.
     */
    public function setConfig(array $config): SendHandle
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * Desc: format message.
     * Date: 2022/7/18
     * Time: 14:52.
     *
     * @param string $url
     */
    private function formatMessage(string $title, string $uri, string $traceId, string $content, string $level): array
    {
        $title = empty($title) ? 'Dear~ 系统监控到【平台】在' . $this->sender->getExpire() . '秒内代码错误超过' . $this->sender->getFrequency() . '次，请尽快修复' : $title;

        $arrLine = [
            ['content' => '### ' . $title],
            ['title' => '> level：', 'content' => $level, 'color' => 'warning'],
            ['title' => '> msg：', 'content' => mb_substr($content, 0, 300), 'color' => 'comment'],
            ['title' => '> uri：', 'content' => $uri, 'color' => 'comment'],
        ];

        if ($traceId) {
            $arrLine[] = ['title' => 'traceId：', 'content' => $traceId, 'color' => 'warning'];
        }

        $arrTextLine = [];
        foreach ($arrLine as $line) {
            $tmp = '';
            if (!empty($line['title'])) {
                $tmp .= $line['title'];
            }
            if (!empty($line['color'])) {
                $tmp .= sprintf('<font color="%s">', $line['color']);
            }
            $tmp .= $line['content'];
            if (!empty($line['color'])) {
                $tmp .= '</font>';
            }
            $arrTextLine[] = $tmp;
        }
        $text = implode("\r\n", $arrTextLine);
        $text = mb_strcut($text, 0, 4090);

        return [
            'msgtype'  => 'markdown',
            'markdown' => [
                'content' => $text,
            ],
        ];
    }
}
