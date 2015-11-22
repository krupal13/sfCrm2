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
        
        $trans = $this->container->get('translator');
        
        $menu = $factory->createItem('root');

        $menu->addChild('panel', [
            'route' => 'homepage',
            'label' => $trans->trans('Panel'),
        ]);

        $menu->addChild('agreement_list', [
            'label' => $trans->trans('Lista umów'),
        ]);
        $menu['agreement_list']->addChild(
                'Umowy na życie', [
            'route' => 'agreememt_life_list',
          'label' => $trans->trans('Umowy na życie')
                ]
        );
        //...
        $menu->addChild('agreement_add', [
            'label' => $trans->trans('Nowa umowa'),
        ]);
        $menu['agreement_add']->addChild(
                'Umowy na życie', [
            'route' => 'agreememt_life_add',
            'label' => $trans->trans('Umowy na życie'),
                ]
        );
        //...

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_MANAGER')) {
            $menu->addChild('Lista agentów', [
                'route' => 'agents_list',
                'label' => $trans->trans('Lista agentów')
            ]);
        }

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_MANAGER')) {
            $menu->addChild('Dodaj klienta', [
                'route' => 'client_add',
                'label' => $trans->trans('Dodaj klienta'),
            ]);
        }
            return $menu;
        }
    }