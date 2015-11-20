<?php 

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\AgreementLifeType;
use AppBundle\Entity\AgreementLife;
use AppBundle\Entity\Attachment;

/**
 * Class AgreementLifeController
 * @Route("/agreement/life")
 */
class AgreementLifeController extends Controller
{
    /**
     * @Route("/list", name="agreememt_life_list")
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction()
    {
        $agreements = $this->getDoctrine()
            ->getRepository('AppBundle:AgreementLife')
            ->findAll();
        
        return $this->render('agreementLife\list.html.twig', [
            'agreements' => $agreements,
        ]);
    }
    
    /**
     * @Route("/show/{slug}", name="agreememt_life_show")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction()
    {
        
    }
    
    /**
     * @Route("/add", name="agreememt_life_add")
     * @Security("has_role('ROLE_AGENT')")
     */
    public function addAction(Request $request)
    {
        $agreement = new AgreementLife();
        $attachment = new Attachment();
        $agreement->addAttachment($attachment);
        
        $user = $this->getUser();
        
        $form = $this->createForm(new AgreementLifeType($user), $agreement);
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            
            if (!$user->hasRole('ROLE_MANAGER')) {
                $agreement->setAgent($user->getDetails());
            }
            $agreement->setNumber('temp');
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($agreement);
            $em->flush();
            
            return $this->redirectToRoute('homepage');
        }
        
        
        return $this->render('agreementLife\add.html.twig', [
            'form' => $form->createView(),
        ]);        
    }
}
