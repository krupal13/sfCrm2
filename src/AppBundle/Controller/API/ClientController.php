<?php

namespace AppBundle\Controller\API;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * ClientController
 */
class ClientController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     * section="Client",
     * resource = true,
     * description= "Gets all Client",
     * output = "AppBundle\Entity\UserDetailsClient",
     * statusCodes = {
     *      200 = "Returned when succesful",
     *      403 = "Not authorized"
     * }
     * )
     * 
     * @return array
     */
    public function cgetAction()
    {
        $clients = $this->getDoctrine()
                ->getRepository('AppBundle:UserDetailsClient')
                ->findAll();
        
        $view = $this->view($clients, 200);
        
        return $this->handleView($view);
        
    }

//    public function getAction($id)
//    {
//        
//        
//    }
//    
//    public function postAction()
//    {
//        
//        
//    }
//   
//    public function putAction($id)
//    {
//        
//        
//    }
//    
//    public function patchAction($id)
//    {
//        
//        
//    }
//    
//    public function deleteAction($id)
//    {
//        
//        
//    }
    
}
