<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\UserDetailsAgent;

/**
 * Class LoadUserAgentData
 */
class LoadUserAgentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const USERS_TO_CREATE = 30;
    
    public static $users = [];
    public static $details = [];
    
    public static $newUserPrototype = [
        'email' => 'agent%d@sfcrm.dev',
        'username' => 'agent%d@sfcrm.dev',
        'password' => 'demo',
        'roles' => [
            'ROLE_AGENT',
        ],
    ];
    
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');
        
        self::$users = [];
        self::$details = [];
        
        $userManager = $this->container->get('fos_user.user_manager');
        
        $willBeAdded = self::USERS_TO_CREATE;
        
        for ($i = 1; $i <= $willBeAdded; $i++) {
            $newUser = $userManager->createUser();
            $newUser
                ->setEmail(sprintf(self::$newUserPrototype['email'], $i))
                ->setUsername(sprintf(self::$newUserPrototype['username'], $i))
                ->setPlainPassword(self::$newUserPrototype['password'])
                ->setRoles(self::$newUserPrototype['roles'])
                ->setEnabled(true)
            ;
            
            $newUserDetails = new UserDetailsAgent();
                
            $newUserDetails->setFirstName($faker->firstName);
            $newUserDetails->setLastName($faker->lastName);
            $newUserDetails->setCommission($faker->numberBetween(6, 20));
            $newUserDetails->setManager(
                $manager->getRepository('AppBundle:UserDetailsManager')->findOneById(LoadUserManagerData::$details[array_rand(LoadUserManagerData::$details)]->getId())
            );
            
            $newUser->setDetails($newUserDetails);
            $userManager->updateUser($newUser);
            
            self::$users[] = $newUser;
            self::$details[] = $newUserDetails;
        }
                
        $manager->clear();
    }
    
    public function getOrder()
    {
        return 1.2;
    }
}
