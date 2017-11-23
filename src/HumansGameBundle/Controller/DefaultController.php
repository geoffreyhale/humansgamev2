<?php

namespace HumansGameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if (null == $this->getUser()) {
            return $this->render('HumansGameBundle::pre_user.html.twig');
        }

        return $this->render('HumansGameBundle::index.html.twig', array(
            'user' => $this->getUser(),
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        dump("test");
        die;
    }
}
