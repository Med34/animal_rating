<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Recorder\VoteRecorder;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VoteController extends AbstractController
{
    #[Route('/', name: 'app_vote', methods: ['GET', 'POST'])]
    public function index(Request $request, VoteRecorder $recorder): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recorder->record($vote);

            $this->addFlash('success', "Merci d'avoir voté");

            return $this->redirectToRoute('app_vote');
        }

        return $this->render('vote/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin', name: 'app_vote_admin', methods: ['GET'])]
    public function admin(VoteRepository $votes): Response
    {
        return $this->render('vote/admin.html.twig', [
            'animalStats' => $votes->getAnimalStatistics(),
        ]);
    }
}
