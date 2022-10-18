<?php


namespace reportMessageTest\reportMessage\handler;


use PHPMailer\PHPMailer\PHPMailer;
use reportMessage\handler\EmailSender;
use reportMessageTest\reportMessage\BaseTestCase;

class EmailSenderTest extends BaseTestCase
{
    private $mail;

    protected function setUp()
    {
        $this->mail = (new class() extends EmailSender {

            public function setRecipients(PHPMailer $mailer): PHPMailer
            {
                return $mailer;
            }

            public function setAttachments(PHPMailer $mailer): PHPMailer
            {
                return $mailer;
            }

            public function setContent(PHPMailer $mailer): PHPMailer
            {
                return $mailer;
            }

            public function config(): array
            {
                return $this->config;
            }
        });
    }

    protected function tearDown()
    {
        $this->mail = null;
    }

    /**
     * @covers \reportMessage\handler\EmailSender::send
     */
    public function test_send()
    {
        $result = $this->mail->send(['hello word!']);
        // 开发环境，允许发送失败
        $this->assertFalse($result);
    }

    /**
     * @covers \reportMessage\handler\EmailSender::setConfig
     */
    public function test_set_config()
    {
        $result = $this->mail->setConfig([
            'mail' => [
                'type'     => 'smtp',
                'host'     => 'localhost',
                'is_auth'  => true,
                'username' => 'test',
                'password' => '1234',
                'tls'      => PHPMailer::ENCRYPTION_STARTTLS,
                'port'     => 433
            ]
        ]);
        $this->assertInstanceOf(EmailSender::class, $result);
        $config = $result->config();
        $this->assertEquals('smtp', $config['mail']['type']);
        $this->assertEquals('localhost', $config['mail']['host']);
        $this->assertTrue($config['mail']['is_auth']);
        $this->assertEquals('test', $config['mail']['username']);
        $this->assertEquals('1234', $config['mail']['password']);
        $this->assertEquals(PHPMailer::ENCRYPTION_STARTTLS, $config['mail']['tls']);
        $this->assertEquals(433, $config['mail']['port']);

        return $config;
    }

    /**
     * @depends test_set_config
     * @covers  \reportMessage\handler\EmailSender::setSetting
     * @param array $config
     */
    public function test_set_setting(array $config)
    {
        $config = $config['mail'];
        $this->mail->setConfig([
            'mail' => [
                'type'     => 'smtp',
                'host'     => 'localhost',
                'is_auth'  => true,
                'username' => 'test',
                'password' => '1234',
                'tls'      => PHPMailer::ENCRYPTION_STARTTLS,
                'port'     => 433
            ]
        ]);
        /**
         * @var \PHPMailer\PHPMailer\PHPMailer $result
         */
        $result = self::callMethod($this->mail, 'setSetting', [new PHPMailer()]);
        $result->getAllRecipientAddresses();
        $this->assertEquals($config['host'], $result->Host);
        $this->assertEquals($config['type'], $result->Mailer);
        $this->assertEquals($config['is_auth'], $result->SMTPAuth);
        $this->assertEquals($config['username'], $result->Username);
        $this->assertEquals($config['password'], $result->Password);
        $this->assertEquals($config['tls'], $result->SMTPSecure);
        $this->assertEquals($config['port'], $result->Port);
    }

    /**
     * @covers \reportMessage\handler\EmailSender::setRecipients
     * @throws \ReflectionException
     */
    public function test_set_recipients()
    {
        $this->assertInstanceOf(PHPMailer::class, $this->mail->setRecipients(new PHPMailer()));
//        $stub = $this->getMockForAbstractClass(EmailSender::class);
//        $stub->expects($this->any())
//            ->method('setRecipients')
//            ->withAnyParameters()
//            ->will($this->returnValue(new PHPMailer()));
//        /**
//         * @var \reportMessage\handler\EmailSender $stub
//         */
//        $this->assertInstanceOf(PHPMailer::class, $stub->getRecipients());
    }

    /**
     * @covers \reportMessage\handler\EmailSender::setAttachments
     * @throws \ReflectionException
     */
    public function test_set_attachments()
    {
        $stub = $this->getMockForAbstractClass(EmailSender::class);
        $stub->expects($this->any())
            ->method('setAttachments')
            ->withAnyParameters()
            ->will($this->returnValue(new PHPMailer()));
        /**
         * @var \reportMessage\handler\EmailSender $stub
         */
        $this->assertInstanceOf(PHPMailer::class, $stub->getRecipients());
    }

    /**
     * @covers \reportMessage\handler\EmailSender::setContent
     * @throws \ReflectionException
     */
    public function test_set_content()
    {
        $stub = $this->getMockForAbstractClass(EmailSender::class);
        $stub->expects($this->any())
            ->method('setContent')
            ->withAnyParameters()
            ->will($this->returnValue(new PHPMailer()));
        /**
         * @var \reportMessage\handler\EmailSender $stub
         */
        $this->assertInstanceOf(PHPMailer::class, $stub->getContent());
    }


    /**
     * @covers \reportMessage\handler\EmailSender::getRecipients
     */
    public function test_get_recipients()
    {
        $result = $this->mail->getRecipients();
        $this->assertInstanceOf(PHPMailer::class, $result);
    }

    /**
     * @covers \reportMessage\handler\EmailSender::getAttachments
     */
    public function test_get_attachments()
    {
        $result = $this->mail->getAttachments();
        $this->assertInstanceOf(PHPMailer::class, $result);
    }

    /**
     * @covers \reportMessage\handler\EmailSender::getContent
     */
    public function test_get_content()
    {
        $result = $this->mail->getContent();
        $this->assertInstanceOf(PHPMailer::class, $result);
    }
}