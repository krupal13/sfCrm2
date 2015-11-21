<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Menu
 */
class MainMenu extends ContainerAware {

    public function adminMenu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        return $menu;
    }

    public function menu(FactoryInterface $factory, array $options) {
        $menu = $factory->createItem('root');

        $menu->addChild('Panel', [
            'route' => 'homepage',
        ]);

        $menu->addChild('agreement_list', [
            'label' => 'Lista umów',
        ]);
        $menu['agreement_list']->addChild(
                'Umowy na życie', [
            'route' => 'agreememt_life_list',
                ]
        );
        //...
        $menu->addChild('agreement_add', [
            'label' => 'Nowa umowa',
        ]);
        $menu['agreement_add']->addChild(
                'Umowy na życie', [
            'route' => 'agreememt_life_add',
                ]
        );
        //...

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_MANAGER')) {
            $menu->addChild('Lista agentów', [
                'route' => 'agents_list',
            ]);
        }

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_MANAGER')) {
            $menu->addChild('Dodaj klienta', [
                'route' => 'client_add',
            ]);
        }
            return $menu;
        }
    }