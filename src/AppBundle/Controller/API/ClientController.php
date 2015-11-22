<?php

namespace AppBundle\Controller\API;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserDetailsClientType;
use AppBundle\Entity\UserDetailsClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

/**
 * Class ClientController
 */
class ClientController extends APIController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *  section="Client",
     *  resource = true,
     *  description = "Gets all Client",
     *  output = "AppBundle\Entity\UserDetailsClient",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Not authorized"
     *  }
     * )
     *
     * @return array
     */
    public function cgetAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->response('Brak dostępu', 403);
        }
        
        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:UserDetailsClient')
            ->findAll();

        $view = $this->view($clients, 200);

        return $this->handleView($view);
    }
    
    /**
     * @ApiDoc(
     *  section="Client",
     *  resource = true,
     *  description = "Get Client by given id",
     *  output = "AppBundle\Entity\UserDetailsClient",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Not authorized",
     *     404 = "Not found"
     *  }
     * )
     *
     * @return UserDetailsClient
     */
    public function getAction($id)
    {
        $client = $this->getDoctrine()
            ->getRepository('AppBundle:UserDetailsClient')
            ->findOneBy([
                'id' => $id,
            ]);
        
        if (!$client) {
            return $this->response('Klient o zadanym ID nie istnieje', 404);
        }

        $view = $this->view($client, 200);

        return $this->handleView($view);
    }
    
    /**
     * @ApiDoc(
     *  section="Client",
     *  resource = true,
     *  description = "Add new Client",
     *  output = "AppBundle\Entity\UserDetailsClient",
     *  input = "AppBundle\Form\UserDetailsClientType",
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     403 = "Not authorized",
     *     400 = "Form error"
     *  }
     * )
     *
     * @return UserDetailsClient
     */
    public function postAction(Request $request)
    {
        $client = new UserDetailsClient();
        $form = $this->createForm(new UserDetailsClientType(), $client, [
            'csrf_protection' => false,
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            
            $view = $this->view($client, 201);

            return $this->handleView($view);
        }
        
        return $this->response('Błędne dane z formularza', 400, $form);
    }
    
    /**
     * @ApiDoc(
     *  section="Client",
     *  resource = true,
     *  description = "Update Client",
     *  output = "AppBundle\Entity\UserDetailsClient",
     *  input = "AppBundle\Form\UserDetailsClientType",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Not authorized",
     *     400 = "Form error",
     *     404 = "Not found"
     *  }
     * )
     *
     * @return UserDetailsClient
     */
    public function putAction($id, Request $request)
    {
        $client = $this->getDoctrine()
            ->getRepository('AppBundle:UserDetailsClient')
            ->findOneBy([
                'id' => $id,
            ]);
        
        if (!$client) {
            return new JsonResponse([
                'message' => 'Klient o zadanym ID nie istnieje',
            ], 404);
        }
        
        if (!$request->request->get('firstName') || !$request->request->get('lastName') || !$request->request->get('pesel')) {
            return new JsonResponse([
                'message' => 'PUT wymaga wszystkich pól',
            ], 400);
        }
        
        $form = $this->createForm(new UserDetailsClientType(), $client);
        
        $form->handleRequest($request);
        
        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            
            $view = $this->view($client, 200);

            return $this->handleView($view);
        //}
        
        return $this->response('Błędne dane z formularza', 400, $form); 
    }
    
    public function patchAction($id)
    {        $client = $this->getDoctrine()
            ->getRepository('AppBundle:UserDetailsClient')
            ->findOneBy([
                'id' => $id,
            ]);
        
        if (!$client) {
            return new JsonResponse([
                'message' => 'Klient o zadanym ID nie istnieje',
            ], 404);
        }
        
        $form = $this->createForm(new UserDetailsClientType(), $client);
        
        $form->handleRequest($request);
        
        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            
            $view = $this->view($client, 200);

            return $this->handleView($view);
        //}
        
        return new JsonResponse([
            'message' => 'Błędne dane z formularza',
            'errors' => [],
        ], 400);        
    }
    
    /**
     * @ApiDoc(
     *  section="Client",
     *  resource = true,
     *  description = "Delete Client",
     *  statusCodes = {
     *     204 = "Returned when successful",
     *     403 = "Not authorized",
     *     404 = "Not found"
     *  }
     * )
     * @param $id Id User to delete
     * 
     * 
     */
    public function deleteAction($id)
    {
        $client = $this->getDoctrine()
            ->getRepository('AppBundle:UserDetailsClient')
            ->findOneBy([
                'id' => $id,
            ]);
        
        if (!$client) {
            return new JsonResponse([
                'message' => 'Klient o zadanym ID nie istnieje',
            ], 404);
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($client);
        $em->flush();
        
        $view = $this->view('', 204);
        
        return $this->handleView($view);
    }
    
    
}
