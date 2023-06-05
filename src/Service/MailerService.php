<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailerService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $adminEmail,
        private readonly string $appName,
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
    ) {
    }

    public function sendReset(User $user, ResetPasswordToken $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->appName))
            ->to($user->getEmail())
            ->subject('email.reset_request.subject')
            ->htmlTemplate('password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        //TODO: check transport exception
        $this->mailer->send($email);
    }

    public function sendConfirmation(User $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $email = (new TemplatedEmail())
                ->from(new Address($this->adminEmail, $this->appName))
                ->to($user->getUsername())
                ->subject('email.confirm.subject')
                ->htmlTemplate('registration/confirmation_email.html.twig');


        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        //TODO: check transport exception
        $this->mailer->send($email);
    }
}
