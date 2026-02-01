<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserVote;
use App\Form\UserVoteType;
use App\Config\GenderType;
use App\Repository\UserVoteRepository;
use App\Repository\CandidateRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/votes')]
final class UserVoteController extends AbstractController {
    #[Route(name: 'app_user_vote_index', methods: ['GET'])]
    public function index(UserVoteRepository $userVoteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user_vote/index.html.twig', [
            'user_votes' => $userVoteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_vote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userVote = new UserVote();
        $form = $this->createForm(UserVoteType::class, $userVote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($userVote);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_vote/new.html.twig', [
            'userVote' => $userVote,
            'form' => $form,
        ]);
    }

    #[Route('/vote', name: 'app_user_vote_vote', methods: ['GET', 'POST'])]
    public function vote(Request $request, CandidateRepository $candidateRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get the user object
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $userVote = new UserVote();
        $userVote->setUser($user);
        $userVote->setCreatedOn(new \DateTime());
        $userVote->setModifiedOn(new \DateTime());

        $candidate_id = $request->query->get('candidate_id');
        $candidate = $candidateRepository->find($candidate_id);
        $userVote->setCandidate($candidate);

        // Determine smash value from GET or POST (support 1/0, yes/no, true/false, on/off)
        $smashRaw = $request->query->get('smash', $request->request->get('smash', null));
        if ($smashRaw !== null)
        {
            $lower = strtolower((string) $smashRaw);
            $smashBool = in_array($lower, ['1', 'true', 'yes', 'on'], true);
            $userVote->setSmash($smashBool);

            // Validate candidate and user before saving
            if (!$candidate)
            {
                $this->addFlash('danger', 'Candidate not found.');
                return $this->redirectToRoute('app_welcome');
            }

            if (!$user instanceof User)
            {
                $this->addFlash('danger', 'You must be logged in to vote.');
                return $this->redirectToRoute('app_login');
            }

            $entityManager->persist($userVote);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        // If we reached here, no immediate vote was submitted; render the vote UI
        $form = $this->createForm(UserVoteType::class, $userVote);
        $form->handleRequest($request);

        // Options for the search form
        // Defaults come from the user's preferences
        $defaultCategory = $user->getPreferredCategory();
        $defaultGender = $user->getPreferredGender();

        // Read search criteria from query parameters (GET form)
        $category = $request->query->get('category', $defaultCategory);
        $gender = $request->query->get('gender', $defaultGender);
        $categories = $categoryRepository->findAll();
        $genderOptions = array_map(fn($g) => $g->value, GenderType::cases());

        return $this->render('home/index.html.twig', [
            'candidate' => $userVote->getCandidate(),
            'categories' => $categories,
            'selectedCategory' => $category,
            'selectedGender' => $gender,
            'genderOptions' => $genderOptions,
        ]);
    }

    #[Route('/results', name: 'app_user_vote_results', methods: ['GET'])]
    public function results(UserVoteRepository $userVoteRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user_vote/results.html.twig', [
            'user_votes' => $userVoteRepository->findAll(),
        ]);
    }

    #[Route('/summary', name: 'app_user_vote_summary', methods: ['GET'])]
    public function summary(CandidateRepository $candidateRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user_vote/summary.html.twig', [
            'candidates' => $candidateRepository->findAll(),
        ]);
    }


    #[Route('/{id}', name: 'app_user_vote_show', methods: ['GET'])]
    public function show(UserVote $userVote): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user_vote/show.html.twig', [
            'user_vote' => $userVote,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_vote_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserVote $userVote, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(UserVoteType::class, $userVote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_vote/edit.html.twig', [
            'user_vote' => $userVote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_vote_delete', methods: ['POST'])]
    public function delete(Request $request, UserVote $userVote, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        if ($this->isCsrfTokenValid('delete' . $userVote->getId(), $request->getPayload()->getString('_token')))
        {
            $entityManager->remove($userVote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_vote_index', [], Response::HTTP_SEE_OTHER);
    }
}
