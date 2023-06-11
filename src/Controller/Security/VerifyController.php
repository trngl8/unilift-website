<?php

namespace App\Controller\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/verify', name: 'app_verify_')]
class VerifyController extends AbstractController
{
    //TODO: add UserService dependency
    public function __construct(
        private EntityManagerInterface $entityManager,
        private VerifyEmailHelperInterface $verifyEmailHelper,
    )
    {
    }

    #[Route('/email', name: 'email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        // TODO use token instead of id
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

            $user->setIsVerified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_verify_success');
    }

    #[Route('/success', name: 'success')]
    public function verifySuccess(): Response
    {
        return $this->render('registration/verify_success.html.twig');
    }
}
