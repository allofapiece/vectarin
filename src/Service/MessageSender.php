<?php

namespace App\Service;


use App\Entity\User;

class MessageSender
{
    private $mailer;
    private $templating;

    /**
     * MessageSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param User $user
     */
    public function sendConfirmationMessage(User $user)
    {
        $message = (new \Swift_Message('Подтверждение регистрации на Victarin.by'))
            ->setFrom('allseeing98@gmail.com')
            ->setTo('allseeing98@gmail.com')
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig', [
                    'name' => $user->getFirstName() . ' ' . $user->getSurname(),
                    'token' => $user->getToken(),
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }


}