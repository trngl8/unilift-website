<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/pages', name: 'pages_')]
class PageController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/{slug}', name: 'show', methods: ['GET', 'HEAD'] )]
    public function show(string $slug): Response
    {
        $page = $this->doctrine->getRepository(Page::class)->findOneBy(['slug' => $slug]);

        if(!$page) {
            throw new NotFoundHttpException(sprintf("Page %s not found", $slug));
        }

        return $this->render('page/show.html.twig', [
            'page' => $page
        ]);
    }
}
