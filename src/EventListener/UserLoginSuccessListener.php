<?php

namespace App\EventListener;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener]
class UserLoginSuccessListener
{
    private ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        $profile = $this->profileRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            $profile = new Profile();
            $profile->setEmail($user->getUserIdentifier());
            $profile->setName($user->getUserIdentifier());
            $profile->setLocale($event->getRequest()->getLocale());
            $profile->setTimezone((new \DateTime())->getTimezone()->getName()); //TODO: get from environment
            $profile->setActive(true);
            //TODO: set activated date
            $this->profileRepository->add($profile);
        }

        //TODO: set last login date
    }
}
