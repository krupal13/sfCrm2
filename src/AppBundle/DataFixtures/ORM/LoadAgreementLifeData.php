<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\AgreementLife;

/**
 * Class LoadAgreementLifeData
 */
class LoadAgreementLifeData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const AGREEMENT_TO_CREATE = 10;
    
    public static $agreement = [];
        
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');
        
        self::$agreement = [];
        
        $willBeAdded = self::AGREEMENT_TO_CREATE;
        
        for ($i = 1; $i <= $willBeAdded; $i++) {
            $newAgreement = new AgreementLife();
            
            $newAgreement->setNumber(str_pad($i, 5, 0, STR_PAD_LEFT));
            $newAgreement->setPerson(sprintf('%s %s', $faker->firstName, $faker->lastName));
            $newAgreement->setValue($faker->randomFloat(500, 4000));
            $newAgreement->setAgent(
                $manager->getRepository('AppBundle:UserDetailsAgent')->findOneById(LoadUserAgentData::$details[array_rand(LoadUserAgentData::$details)]->getId())
            );
            $newAgreement->setClient(
                $manager->getRepository('AppBundle:UserDetailsClient')->findOneById(LoadUserClientData::$details[array_rand(LoadUserClientData::$details)]->getId())
            );
                            
            $manager->persist($newAgreement);
            
            self::$agreement[] = $newAgreement;
        }
                
        $manager->flush();
        $manager->clear();
    }
    
    public function getOrder()
    {
        return 2.1;
    }
}
