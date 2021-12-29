<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    #[Route('/conference', name: 'conference')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // instanciando Conference
        $conference = new Conference();
        $conference->setCity('Germany');
        $conference->setYear('2021');
        $conference->setIsInternational(true);

        // instanciando Comments

        $comment = new Comment();
        $comment->setAuthor('Alex');
        $comment->setCreatedAt(date_create_immutable( 'now'));
        $comment->setEmail('test@gmail.com');
        $comment->setText('lorem impsun sdfdf erfgd dfgdfgdfg ');


        // relation
        $comment->setConference($conference);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($conference);
        $entityManager->persist($comment);
        $entityManager->flush();


        return new Response(
            'new comment in the conference: '.$conference->getCity().
            ' by:  '.$comment->getAuthor()
        );

    }

    #[Route('/', name: 'home')]
    // llamamdo al repositorio conference para acceder a los queries;
    public function home(ConferenceRepository $conferenceRepository): Response
    {
        $conferences = $conferenceRepository->findAll();
        return$this->render('conference/index.html.twig', [ 'conferences' => $conferences]);
    }

    #[Route('/', name: 'home')]
    public function showComments(CommentRepository $commentRepository): Response{

    }

}
