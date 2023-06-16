<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{

    #[Route('/info', name: 'app_info', methods: ['GET'])]
    public function info(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('default/info.html.twig');
    }
}
