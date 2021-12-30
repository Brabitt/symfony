<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $conference->setCity('Germany');
        $conference->setYear('2021');
        $conference->setIsInternational(true);

        // instancing Comments

        $comment = new Comment();
        $comment->setAuthor('Gavi');
        $comment->setCreatedAt(date_create_immutable('now'));
        $comment->setEmail('Gavi@gmail.com');
        $comment->setText('nice work!');


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
    public function show(Request $request, Environment $twig, CommentRepository $commentRepository, Conference $conference): Response
    // function that returns the comments of the conferences with a paginator for the comments .
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->CommentPaginator($conference, $offset);

        return new Response($twig->render('conference/showComments.html.twig',
             [
                 'conference' => $conference,
                 'comments' => $paginator,
                 'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
                 'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
             ]
            )
        );
    }

}
