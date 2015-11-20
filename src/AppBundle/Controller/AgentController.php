<?php namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class AgentController
 */
class AgentController extends Controller
{
    /**
     * @Route("/agent/list", name="agents_list")
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function listAction(Request $request)
    {
        $agents = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:UserDetailsAgent')
            ->getAgents($this->getUser(), true);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $agents,
            $request->query->getInt('page', 1)
        );
        
        
        return $this->render('agent/list.html.twig', [
            'agents' => $pagination,
        ]);
    }
}
