<?php
// src/Menu/MenuBuilder.php
namespace App\Menu;

use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuBuilder {
    private $factory;
    private $security;
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, Security $security, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
    }

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav w-100');

        $menu->addChild('Smash or Pass', ['route' => 'app_welcome'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Candidates', ['route' => 'app_candidate_index'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Users', ['route' => 'app_user_index'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Categories', ['route' => 'app_category_index'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Voting', ['route' => 'app_user_vote_summary'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        // Add user indicator on the right
        $user = $this->security->getUser();

        if ($user && $user instanceof User)
        {
            $label = 'Welcome ' . ($user->getNickName() ?? $user->getFirstName() ?? $user->getEmail());
            $user_id = $user->getId();

            $user = $menu->addChild('user', ['uri' => '#'])
                ->setLabel($label)
                ->setAttribute('class', 'nav-item dropdown flex-grow-1 align-self-end text-end')
                ->setLinkAttribute('id', 'welcomeDropdown')
                ->setLinkAttribute('class', 'nav-link dropdown-toggle')
                ->setLinkAttribute('data-bs-toggle', 'dropdown')
                ->setLinkAttribute('role', 'button')
                ->setLinkAttribute('aria-expanded', 'false')
                ->setChildrenAttribute('class', 'dropdown-menu')
                ->setChildrenAttribute('aria-labelledby', 'welcomeDropdown');

            $user->addChild('View Profile', [
                'route' => 'app_user_show',
                'routeParameters' => ['id' => $user_id]
            ])
                ->setAttribute('class', 'dropdown-item')
                ->setLinkAttribute('class', 'dropdown-item');

            $user->addChild('Edit Profile', [
                'route' => 'app_user_edit',
                'routeParameters' => ['id' => $user_id]
            ])
                ->setAttribute('class', 'dropdown-item')
                ->setLinkAttribute('class', 'dropdown-item');

            $user->addChild('Logout', ['route' => 'app_logout'])
                ->setAttribute('class', 'dropdown-item')
                ->setLinkAttribute('class', 'dropdown-item');

        }
        else
        {
            // Show Register and Login links aligned to right
            $menu->addChild('Register', ['route' => 'app_register'])
                ->setAttribute('class', 'nav-item ms-auto')
                ->setLinkAttribute('class', 'nav-link');

            $menu->addChild('Login', ['route' => 'app_login'])
                ->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }
}
