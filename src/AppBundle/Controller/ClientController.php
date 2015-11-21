<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserDetailsClientType;
use AppBundle\Entity\UserDetailsClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ClientController
 */
class ClientController extends Controller
{
    public function listAction() 
    {
        
    }
    
    /**
     * @Route("/client/add", name="client_add")
     * @Security("has_role('ROLE_AGENT')")
     */
    public function addAction(Request $request) {
        $client = new UserDetailsClient();
        
        $form = $this->createForm(new UserDetailsClientType(), $client);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->flush();
            
            $this->redirectToRoute('homepage');
        }
        
        return $this->render('client/add.html.twig', [
            'form' => $form->createView(),
        ]);
        
        }
}
