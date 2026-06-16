<?php
// src/Menu/MenuBuilder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class VotingMenu {
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        $menu = $this->factory->createItem('submenu');
        //$menu->setChildrenAttribute('class', 'nav-secondary w-100');
       // $menu->setAttribute('class', 'nav-secondary');
        $menu->setChildrenAttribute('class', 'navbar-nav nav-secondary mb-2 p-2 w-100');
        $menu->setChildrenAttribute('style', 'background-color: #d6d6d6');

        $menu->addChild('Summary', ['route' => 'app_user_vote_summary'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Results', ['route' => 'app_user_vote_results'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');
        $menu->addChild('Listing', ['route' => 'app_user_vote_index'])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
