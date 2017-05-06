<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/{post}", name="post")
     * @Template("OneThirtyWordsBundle:Post:index.html.twig")
     */
    public function indexAction(Post $post)
    {
        if ($this->getUser() !== $post->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your post.");
        }

        return [
            'post' => $post,
        ];
    }
}
