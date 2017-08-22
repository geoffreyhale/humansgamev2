<?php

namespace OneThirtyWordsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/by-130s", name="getUsersBy130s")
     */
    public function getUsersBy130sAction($min130s = 0, $limit = 0)
    {
        return $this->render('OneThirtyWordsBundle:Users:partials/users_by_130s.html.twig', array(
            'users' => $this->get('one_thirty_service')->getUsersWith130WordsCounts($min130s, $limit),
        ));
    }
}