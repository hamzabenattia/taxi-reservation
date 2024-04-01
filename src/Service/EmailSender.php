<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailSender
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }





    public function sendEmail($sender,$recipient ,$subject, $html, $context)
    {
        $email = (new TemplatedEmail())
                ->from(new Address($sender))
                ->to(new Address($recipient))
                ->subject($subject)

                // path of the Twig template to render
                ->htmlTemplate($html)

                // change locale used in the template, e.g. to match user's locale
                ->locale('fr')

                // pass variables (name => value) to the template
                ->context($context);

            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $e) {

                dd($e->getMessage());
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
    }
}