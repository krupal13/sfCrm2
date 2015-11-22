<?php

namespace AppBundle\Controller\API;

use AppBundle\Controller\API\APIController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * AuthController
 */
class AuthController extends APIController implements ClassResourceInterface
{

    /**
     * @ApiDoc(
     *  section="Auth",
     *  resource = true,
     *  description = "login into RestApi",
     *  output = "AppBundle\Entity\UserDetailsClient",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Not authorized"
     *  }
     * )
     *
     * @return array
     */
    public function loginAction(Request $request) {

        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if (!$username || !$password) {
            return $this->response('Błędne dane logowanie');
        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
                ->findOneBy([
            'username' => $username
        ]);
        
        if (!$user) {
            return $this->response('Błędne dane logowania');    
        }
        
        $factory = $this->get('security.encoder_factotry');
        $encoder = $factory->getEncoder($user);
        $valid =$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
        
        if (!$user) {
            return $this->response('Błędne dane logowania');    
        }
        
        $apiKey = md5(uniqid() . time());
        
        $user->serApiKey($apiKey);
        $em->persist($user);
        $em->flush();

        return $this->response('Login successful', 200, [
                    'api_key' => '',
        ]);
    }
    
    public function logoutAction(Request $request)
    {
            
        
    }

}
