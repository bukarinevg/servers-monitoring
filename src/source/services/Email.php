<?php

namespace app\source\services;

use app\source\SingletonTrait;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Throwable;

class Email
{
    use SingletonTrait;
    private PHPMailer $mail;

    public function __construct(array $config)
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $config['host'];
        $this->mail->Port = $config['port'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $config['username'];
        $this->mail->Password = $config['password'];
    }

    public function sendEmail(string $to, string $subject, string $content): bool
    {
        try {
            $this->mail->setFrom($this->mail->Username);
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $content;
            $this->mail->send();

            return true;
        }
        catch (Throwable $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->mail->ErrorInfo;
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
}