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
    #[Route('/addData', name: 'addData')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // instancing Conference
        $conference = new Conference();
        $conference->setCity('Peru');
        $conference->setYear('2000');
        $conference->setIsInternational(true);

        // instancing Comments

        $comment = new Comment();
        $comment->setAuthor('Alicia');
        $comment->setCreatedAt(date_create_immutable('now'));
        $comment->setEmail('Alicia@gmail.com');
        $comment->setText('great job!');


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
    // calling the conference repository to access the queries;
    public function home(Environment $twig,ConferenceRepository $conferenceRepository): Response
    {
        $conferences = $conferenceRepository->findAll();
        return new Response($twig->render('conference/index.html.twig', [ 'conferences' => $conferences
        ]));
    }

    #[Route('/conference/{id}', name: 'conference')]
    public function show(Environment $twig, CommentRepository $commentRepository, Conference $conference): Response
    // function that returns the comments of the conferences.
    {
        $comments = $commentRepository->findBy(
            ['conference' => $conference],
            ['createdAt' => 'DESC']
        );
        return new Response($twig->render('conference/showComments.html.twig',
             ['conference' => $conference,
              'comments' => $comments
             ]));
    }

}
