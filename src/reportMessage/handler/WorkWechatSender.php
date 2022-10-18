<?php

declare(strict_types=1);

/*
 * This file is part of the report-message-bot package.
 */

namespace reportMessage\handler;

use GuzzleHttp\Client;

/**
 * Class WorkWechatSender.
 *
 * @see https://developer.work.weixin.qq.com/document/path/91770#%E6%B6%88%E6%81%AF%E5%8F%91%E9%80%81%E9%A2%91%E7%8E%87%E9%99%90%E5%88%B6
 */
class WorkWechatSender implements ISendHandler
{
    /**
     * config.
     *
     * @var array
     */
    protected $config = [
        'work_wechat_bot' => [
            'bot_url' => '',
            'bot_key' => '',
        ],
    ];

    /**
     * Desc: send data
     * Date: 2022/7/18
     * Time: 14:49.
     * @param  array                                 $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return bool
     */
    public function send(array $data): bool
    {
        $text = $this->formatMessage($data['title'] ?? '', $data['uri'] ?? '', $data['traceId'] ?? '',
            $data['content'] ?? '', $data['level'] ?? 'warning');

        $client     = new Client();
        $botAddress = $this->config['work_wechat_bot']['bot_url'] . '?key=' . $this->config['work_wechat_bot']['bot_key'];
        $response   = $client->request('POST', $botAddress, ['json' => $text, 'verify' => false]);
        $status     = $response->getStatusCode();
        if (200 == $status) {
            return true;
        }

        return false;
    }

    /**
     * Desc: set send config.
     * Date: 2022/7/18
     * Time: 14:48.
     * @param  array        $config
     * @return ISendHandler
     */
    public function setConfig(array $config): ISendHandler
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * Get config.
     * @return array
     */
    public function config(): array
    {
        return $this->config;
    }

    /**
     * Desc: format message.
     * Date: 2022/7/18
     * Time: 14:52.
     * @param  string $title
     * @param  string $uri
     * @param  string $traceId
     * @param  string $content
     * @param  string $level
     * @return array
     */
    private function formatMessage(string $title, string $uri, string $traceId, string $content, string $level): array
    {   //  'Dear~ 系统监控到【平台】在' . $this->sender->getExpire() . '秒内代码错误超过' . $this->sender->getFrequency() . '次，请尽快修复'
        //$title = empty($title) ? : $title;
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
