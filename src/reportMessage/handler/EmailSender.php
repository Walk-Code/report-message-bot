<?php

declare(strict_types=1);

/*
 * This file is part of the order-message package.
 */

namespace reportMessage\handler;

use PHPMailer\PHPMailer\PHPMailer;

abstract class EmailSender implements ISendHandler
{
    // mail server config
    private $config = [
        'mail' => [
            'type'     => '',
            'host'     => '',
            'is_auth'  => true,
            'username' => '',
            'passwrod' => '',
            'tls'      => PHPMailer::ENCRYPTION_SMTPS,
            'port'     => 465,
        ],
    ];

    final public function send(array $data): bool
    {
        $mail = new PHPMailer(true);
        try {
            $this->setSetting($mail);
            // Recipients
//            $mail->setFrom('312430881@qq.com', 'Mailer');
//            $mail->addAddress('walk_code@163.com', 'Joe User');
//            $mail->addAddress('@example.com');
//            $mail->addReplyTo('info@example.com', 'Infomation');
            // $mail->addBCC();
            // $mail->addCC();
            $this->setRecipients($mail);
            // Attachments
            $this->setAttachments($mail);

            // Content
            $this->setContent($mail);
//            $mail->isHTML(true);
//            $mail->Subject = 'Here is the subject';
//            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->send();

            return true;
        } catch (\Exception $e) {
            echo $mail->ErrorInfo;

            return false;
        }
    }

    public function setConfig(array $config): ISendHandler
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * set recipients.
     */
    abstract public function setRecipients(PHPMailer $mailer): PHPMailer;

    /**
     * set attachments.
     */
    abstract public function setAttachments(PHPMailer $mailer): PHPMailer;

    /**
     * set content.
     */
    abstract public function setContent(PHPMailer $mailer): PHPMailer;

    /**
     * mail server setting.
     */
    private function setSetting(PHPMailer $mail): PHPMailer
    {
        $config = $this->config['mail'];
        if (!empty($config['type'])) {
            $mail->isSMTP();
        }
        if (!empty($host = $config['host'])) {
            $mail->Host = $host;
        }
        $mail->SMTPAuth   = $config['is_auth'];
        $mail->Username   = $config['username'];
        $mail->Password   = $config['passwrod'];
        $mail->SMTPSecure = $config['tls'];
        $mail->Port       = $config['port'];

        return $mail;
    }
}
