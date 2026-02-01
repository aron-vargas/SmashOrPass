<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Config\GenderType;
use App\Repository\CategoryRepository;

class SecurityController extends AbstractController {
    #[Route(path: '/', name: 'app_welcome')]
    public function index(Request $request, CandidateRepository $candidateRepository, CategoryRepository $categoryRepository): Response
    {
        // Check that the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get the user object
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Defaults come from the user's preferences
        $defaultCategory = $user->getPreferredCategory();
        $defaultGender = $user->getPreferredGender();

        // Read search criteria from query parameters (GET form)
        $category = $request->query->get('category', $defaultCategory);
        $gender = $request->query->get('gender', $defaultGender);

        // Pick a candidate deterministically using a seeded selection (stable per user + filters)
        $seed = crc32($user->getEmail() . '|' . $category . '|' . $gender);
        $candidate = $candidateRepository->findRandomByCategoryAndGender($category, $gender, $seed);

        // Options for the search form
        $categories = $categoryRepository->findAll();
        $genderOptions = array_map(fn($g) => $g->value, GenderType::cases());

        return $this->render('home/index.html.twig', [
            'candidate' => $candidate,
            'categories' => $categories,
            'selectedCategory' => $category,
            'selectedGender' => $gender,
            'genderOptions' => $genderOptions,
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
