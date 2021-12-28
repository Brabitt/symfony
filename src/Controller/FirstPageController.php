<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstPageController extends AbstractController
{
    #[Route('/noticias', 'first_page')]
    public function index(): Response
    {
        return $this->render('first_page/index.html.twig', [
            'controller_name' => 'FirstPageController',
        ]);
    }

    #[Route('/noticias/{id}', name: "news_page")]
    public function newsSingle($id)
    {
        return $this->render('first_page/newsSingle.html.twig', [
            'idNoticias' => $id,
        ]);
    }
}
