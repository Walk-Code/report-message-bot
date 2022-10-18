<?php

declare(strict_types=1);

/*
 * This file is part of the report-message-bot package.
 */

namespace reportMessage\handler;

use PHPMailer\PHPMailer\PHPMailer;

abstract class EmailSender implements ISendHandler
{
    // mail server config
    protected $config = [
        'mail' => [
            'type'     => '',
            'host'     => '',
            'is_auth'  => true,
            'username' => '',
            'password' => '',
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
//            $mail->setFrom('test@qq.com', 'Mailer');
//            $mail->addAddress('test1@163.com', 'Joe User');
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
     * @param  PHPMailer $mailer
     * @return PHPMailer
     */
    abstract public function setRecipients(PHPMailer $mailer): PHPMailer;

    /**
     * set attachments.
     * @param  PHPMailer $mailer
     * @return PHPMailer
     */
    abstract public function setAttachments(PHPMailer $mailer): PHPMailer;

    /**
     * set content.
     * @param  PHPMailer $mailer
     * @return PHPMailer
     */
    abstract public function setContent(PHPMailer $mailer): PHPMailer;

    /**
     * get recipients.
     * @return PHPMailer
     */
    public function getRecipients(): PHPMailer
    {
        return $this->setRecipients(new PHPMailer());
    }

    /**
     * set attachments.
     * @return PHPMailer
     */
    public function getAttachments(): PHPMailer
    {
        return $this->setAttachments(new PHPMailer());
    }

    /**
     * set content.
     * @return PHPMailer
     */
    public function getContent(): PHPMailer
    {
        return $this->setContent(new PHPMailer());
    }

    /**
     * mail server setting.
     * @param  PHPMailer $mail
     * @return PHPMailer
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
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['tls'];
        $mail->Port       = $config['port'];

        return $mail;
    }
}
