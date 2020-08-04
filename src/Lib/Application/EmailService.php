<?php
namespace App\Lib\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class EmailService extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $subject, string $from, string $to, string $template, array $data)
    {
        $email = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'templates/emails/' . $template. 'html.twig',
                    ['data' => $data]
                ),
                'text/html'
            );

        $this->mailer->send($email);
    }
}