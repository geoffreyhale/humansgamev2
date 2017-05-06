<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/post/{post}", name="post")
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

    /**
     * @Route("/posts", name="posts")
     * @Template("OneThirtyWordsBundle:Post:posts.html.twig")
     */
    public function postsAction()
    {
        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)->findBy([
            'user' => $this->getUser()
        ]);

        return [
            'posts' => array_reverse($posts),
        ];
    }
}
