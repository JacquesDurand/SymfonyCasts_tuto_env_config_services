<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(QuestionRepository $repository)
    {

        $questions = $repository->findAllAskedOrderedByNewest();

        /*
        // fun example of using the Twig service directly!
        $html = $twigEnvironment->render('question/homepage.html.twig');

        return new Response($html);
        */

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {



        return new Response('Sounds like a great feature for v2');

    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     * @return Response
     */
    public function show(Question $question)
    {

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager) {

        $direction = $request->request->get('direction');

        if ($direction === 'up') {
            $question->upVote();
        }
        elseif ($direction ==='down')
        {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute("app_question_show",[
            'slug' => $question->getSlug()
        ]);

    }
}
