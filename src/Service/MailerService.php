<?php

namespace App\Service;

use App\Entity\User;
use App\Model\OrderProduct;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailerService
{
    public function __construct(
        private readonly string $adminEmail,
        private readonly string $appName,
        private readonly MailerInterface $mailer,
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function sendReset(User $user, ResetPasswordToken $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->appName))
            ->to($user->getEmail())
            ->subject($this->translator->trans('email.reset_request.subject'))
            ->htmlTemplate('email/reset_password.html.twig')
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
                ->subject($this->translator->trans('email.confirm.subject'))
                ->htmlTemplate('email/confirmation.html.twig');


        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        //TODO: check transport exception
        $this->mailer->send($email);
    }

    public function sendOrderCreated(OrderProduct $orderProduct): void
    {
        //TODO: set Order parameter
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->appName))
            ->to($orderProduct->email)
            ->subject($this->translator->trans('email.product.order'))
            ->htmlTemplate('email/order_product.html.twig'); //TODO create template

        //TODO: check transport exception
        //$this->mailer->send($email);
    }

    public function notifyAdmin(OrderProduct $orderProduct): void
    {
        //TODO: set Order parameter
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminEmail, $this->appName))
            ->to($this->adminEmail)
            ->subject($this->translator->trans('email.notify.order'))
            ->htmlTemplate('email/new_order.html.twig') //TODO create template
            ->context([
                'order' => $orderProduct,
            ])
        ;

        //TODO: check transport exception
        //$this->mailer->send($email);
    }
}
