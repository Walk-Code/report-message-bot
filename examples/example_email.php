<?php

use PHPMailer\PHPMailer\PHPMailer;
use reportMessage\enum\LogLevelEnum;
use reportMessage\ReportMessage;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$host  = '10.8.8.83';
$port  = '6379';
$auth  = 'root';
$redis = new \Redis();
$redis->connect($host, $port, 10);
$redis->auth($auth);
$redis->select(0);

class CustomEmail extends \reportMessage\handler\EmailSender
{
    public function setRecipients(PHPMailer $mailer): PHPMailer
    {
        $mailer->setFrom('312430881@qq.com', 'Mailer');
        $mailer->addAddress('walk_code@163.com', 'Joe User');
//        $mailer->addAddress('@example.com');
//        $mailer->addReplyTo('info@example.com', 'Infomation');
//        $mailer->addBCC();
//        $mailer->addCC();
        return $mailer;
    }

    public function setAttachments(PHPMailer $mailer): PHPMailer
    {
//        $mailer->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mailer->addAttachment('/tmp/image.jpg', 'new.jpg');
        return $mailer;
    }

    public function setContent(PHPMailer $mailer): PHPMailer
    {
        $mailer->isHTML(true);
        $mailer->Subject = 'Here is the subject';
        $mailer->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mailer;
    }
}

ReportMessage::getInstance()->sendMessage((new CustomEmail()), []);
