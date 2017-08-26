<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/{user}", name="getUser", requirements={"user": "\d+"})
     */
    public function userAction(User $user)
    {
        if ($this->getUser() !== $user) {
            throw new \Exception("ACCESS DENIED: Access to user profiles is currently limited to your own.");
        }

        return $this->render('OneThirtyWordsBundle:User:user.html.twig', array(
            'user' => $user,
        ));
    }
}