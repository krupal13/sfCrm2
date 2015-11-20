<?php 

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Agreement;

/**
 * Class Agreement
 */
class AgreementController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Agreement $agreement)
    {
        return $this->render('agreement\show.html.twig', [
            'agreement' => $agreement,
        ]);
    }
}
