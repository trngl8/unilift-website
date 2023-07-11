<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as AbstractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private AbstractRepository $repository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->repository = $profileRepository;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            $this->addFlash('warning', 'flash.warning.not_found');

            return $this->redirectToRoute('app_profile_new');
        }

        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/new', name: 'app_profile_new')]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            $profile = (new Profile())
                ->setEmail($user->getUserIdentifier())
                ->setActive(true);
        }

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $profile->setEmail($user->getUserIdentifier());
            $this->repository->add($profile);

            $this->addFlash('success', 'flash.success.profile_created');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            $this->addFlash('warning', 'flash.warning.not_found');

            return $this->redirectToRoute('app_profile_new');
        }

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $profile->setEmail($user->getUserIdentifier()); //TODO: check if this is needed
            $this->repository->add($profile);

            $this->addFlash('success', 'flash.success.profile_updated');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }
}
