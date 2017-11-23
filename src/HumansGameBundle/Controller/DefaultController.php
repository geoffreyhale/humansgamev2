<?php

namespace HumansGameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if ($user) {
            //@todo (hydration) there must be a better way
            foreach ($user->getHumans() as $human) { }
        }

        return $this->render('HumansGameBundle::index.html.twig', array(
            'user' => $this->getUser(),
        ));
    }
}
