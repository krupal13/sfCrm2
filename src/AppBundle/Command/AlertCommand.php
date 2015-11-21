<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Agreement;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Alert;

/**
 * Class AlertCommand
 */
class AlertCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('agreement:check')
            ->setDescription('Send notification')
            ->addArgument(
                'agreement',
                InputArgument::OPTIONAL,
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {       
        /**
         * @var EntityManager $em
         */
        $em = $this->getEntityManager();
        
        $agreementOptions = $input->getArgument('agreement');
        
        if (in_array($agreementOptions, Agreement::getTypes()) || $agreementOptions == null) {
            
            switch ($agreementOptions) {
                case Agreement::AGR_TYPE_LIFE: 
                    $repo = 'AppBundle:AgreementLife';
                    
                    break;
                
                case Agreement::AGR_TYPE_ESTATE:
                    $repo = 'AppBundle:AgreementEstate';
                    
                    break;
                
                case Agreement::AGR_TYPE_VEHICLE:
                    $repo = 'AppBundle:AgreementVehicle';
                    
                    break;
                
                default:
                    $repo = 'AppBundle:Agreement';
                    
                    break;
            }
            
            $date = new \DateTime('now');
            $date->modify('-7 days');
            $date->format('Y-m-d');
            
            $qb = $em->createQueryBuilder();
            $qb
                ->select('a')
                ->from($repo, 'a')
                ->where('a.createdAt < :date')
                ->setParameter('date', $date);
            
            $agreements = $qb->getQuery()->getResult();
            $agrCounter = 0;
            
            foreach ($agreements as $agreement) {
                $alert = new Alert();
                
                $subject = 'Przypomnienie o wygaśnięciu umowy';
                $body = sprintf('Umowa o numerze %s wygaśnie za 7 dni.', $agreement->getNumber());
                
                $alert->setTitle($subject);
                $alert->setDescription($body);
                $alert->setIsRead(false);
                $alert->setUser($agreement->getAgent());
                              
                $em->persist($alert);
                $agrCounter++;
                
                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($this->getContainer()->getParameter('mailer_from'))
                    ->setTo($agreement->getAgent()->getUser()->getEmail())
                    ->setBody($body)
    //                ->setBody(
    //                    $this->get('templating')
    //                        ->renderResponse('widok', [
    //                            parametry
    //                        ]),
    //                    'text/html'                    
    //                )
                ;
            
                $this->get('mailer')->send($message);
            }
            
            $em->flush();
            $output->writeLn(sprintf('W %d umowach zbiża się okres wygaśnięcia', $agrCounter));
                
        } else {
            $output->writeLn('Umowa o takim typie nie istnieje');
        }
        
        
        
    }
    
    private function get($id)
    {
        return $this->getContainer()->get($id);
    }
    
    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }
    
    
}
