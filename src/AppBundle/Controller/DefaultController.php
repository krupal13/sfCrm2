<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    /**
     * 
     * @Route("/download/{filename}", name="file_download")
     */
    public function downloadFileAction($filename)
    {
        $filesystem = $this->get('dropbox_alias');
        
        $response = new \Symfony\Component\HttpFoundation\Response();
        
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        
        $response->send();
        
        $response->setContent($filesystem->read($filename));
        
        return $response;
    }
}
