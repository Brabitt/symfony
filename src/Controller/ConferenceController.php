<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    #[Route('/conference', name: 'conference')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // instanciando Conference
        $conference = new Conference();
        $conference->setCity('Spain');
        $conference->setYear('2019');
        $conference->setIsInternational(true);

        // instanciando Comments

        $comment = new Comment();
        $comment->setAuthor('Ronny Anchaluisa');
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
}
