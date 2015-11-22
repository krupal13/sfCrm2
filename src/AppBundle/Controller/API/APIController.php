<?php 

namespace AppBundle\Controller\API;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

/**
 * Class APIController
 */
class APIController extends FOSRestController
{
    public function response($message, $code = 400, $additionalData = null)
    {
        if ($additionalData instanceof Form) {
            $additionalData = $this->getFormErrors($additionalData);
        }
        
        $data = [
            'message' => $message,
        ];
        
        if (null != $additionalData) {
            $data['additionalMessage'] = $additionalData;
        }
        
        return new JsonResponse($data, $code);
    }
    
    private function getFormErrors(Form $form)
    {
        $errors = [];
        if ($err = $this->childErrors($form)) {
            $errors["form"] = $err;
        }
        //
        foreach ($form->all() as $key => $child) {
            //
            if ($err = $this->childErrors($child)) {
                $errors[$key] = $err;
            }
        }
        return $errors;
    }
    /**
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    private function childErrors(Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            
            $message = $error->getMessage();
            array_push($errors, $message);
        }
        return $errors;
    }
}
