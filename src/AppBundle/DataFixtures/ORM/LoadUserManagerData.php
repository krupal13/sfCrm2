<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\UserDetailsManager;

/**
 * Class LoadUserManagerData
 */
class LoadUserManagerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const USERS_TO_CREATE = 10;
    
    public static $users = [];
    public static $details = [];
    
    public static $newUserPrototype = [
        'email' => 'manager%d@sfcrm.dev',
        'username' => 'manager%d@sfcrm.dev',
        'password' => 'demo',
        'roles' => [
           'ROLE_MANAGER', 'ROLE_AGENT',
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
            
            $newUserDetails = new UserDetailsManager();
                
            $newUserDetails->setFirstName($faker->firstName);
            $newUserDetails->setLastName($faker->lastName);
            $newUserDetails->setSalary($faker->numberBetween(3000, 7000));
            $newUserDetails->setArea($faker->numberBetween(0, 3));
                        
            $newUser->setDetails($newUserDetails);
            $newUserDetails->setUser($newUser);
            $userManager->updateUser($newUser);
            
            self::$users[] = $newUser;
            self::$details[] = $newUserDetails;
        }
                
        $manager->clear();
    }
    
    public function getOrder()
    {
        return 1.1;
    }
}
