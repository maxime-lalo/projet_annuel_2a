<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../.env";
class Mailer
{
    /**
     * Mailer constructor.
     * @param string $receiver
     * @param string $object
     * @param string $message
     */
    public function __construct(string $receiver, string $object, string $message)
    {

        $transport = (new Swift_SmtpTransport(SMTP_URL, SMTP_PORT))
            ->setUsername(SMTP_USERNAME)
            ->setPassword(SMTP_PASSWORD)
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($object))
            ->setFrom(['drivncook@gmail.com' => 'DrivNCook'])
            ->setTo([$receiver])
            ->setBody($message)
        ;

        $message->setContentType("text/html");

        $result = $mailer->send($message);
    }
}