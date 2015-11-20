<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\AgreementEstate;

/**
 * Class LoadAgreementEstateData
 */
class LoadAgreementEstateData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
            $newAgreement = new AgreementEstate();
            
            $newAgreement->setNumber(str_pad($i + 22, 5, 0, STR_PAD_LEFT));
            $newAgreement->setEstate($faker->address);
            $newAgreement->setValue($faker->randomFloat(500, 1400));
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
        return 2.3;
    }
}
