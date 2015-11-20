<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserAdminData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const USERS_TO_CREATE = 1;
    
    public static $users = [];
    
    public static $newUserPrototype = [
        'email' => 'admin%d@sfcrm.dev',
        'username' => 'admin%d@sfcrm.dev',
        'password' => 'demo',
        'roles' => [
            'ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_AGENT',
        ],
    ];
    
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        self::$users = [];
        
        $userManager = $this->container->get('fos_user.user_manager');
        
        $willBeAdded = self::USERS_TO_CREATE;
        $allAdded = 0;
        
        for ($i = 1; $i <= $willBeAdded; $i++) {
            $newUser = $userManager->createUser();
            $newUser
                ->setEmail(sprintf(self::$newUserPrototype['email'], $i))
                ->setUsername(sprintf(self::$newUserPrototype['username'], $i))
                ->setPlainPassword(self::$newUserPrototype['password'])
                ->setRoles(self::$newUserPrototype['roles'])
                ->setEnabled(true)
            ;
            
            $userManager->updateUser($newUser);
            
            self::$users[] = $newUser;
            
            $allAdded++;            
        }
                
        $manager->clear();
    }
    
    public function getOrder()
    {
        return 1.0;
    }
}
