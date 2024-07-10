<?php

namespace app\source\services;

use app\source\SingletonTrait;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Throwable;

/**
 * Class Email
 * 
 * This class is responsible for sending emails.
 */
class Email
{
    use SingletonTrait;
    /**
     * @var PHPMailer $mail The PHPMailer object.
     */
    private PHPMailer $mail;

    /**
     * Email constructor.
     * 
     * @param array $config The configuration array.
     */
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

    /**
     * Sends an email.
     *
     * @param string|array $to The recipient of the email.
     * @param string $subject The subject of the email.
     * @param string $content The content of the email.
     * @return bool Returns true if the email was sent successfully, false otherwise.
     * @throws Exception
     */
    public function sendEmail(string | array $to, string $subject, string $content): bool
    {
        try {
            $this->mail->setFrom($this->mail->Username);
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $this->mail->addAddress($recipient);
                }
            }
            else{
                $this->mail->addAddress($to);
            }
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $content;
            $this->mail->send();
            $this->mail->clearAddresses();
            return true;
        }
        catch (Throwable $e) {
            throw new Exception('Error: ' . $e->getMessage());
        }
    }
}