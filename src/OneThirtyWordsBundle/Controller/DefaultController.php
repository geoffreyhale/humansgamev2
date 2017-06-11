<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Post;
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
     * @Template("OneThirtyWordsBundle:Default:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('today'),
            'user' => $this->getUser(),
        ]);

        $wordcount = 0;
        foreach ($posts as $post) {
            $wordcount += str_word_count($post->getBody());
        }

        $categories = $this->getUser()->getCategories()->getValues();

        uasort($categories, function ($c1, $c2) {
            if ($c2->getName() == $c1->getName()) return 0;
            return $c2->getName() > $c1->getName() ? -1 : 1;
        });

        return [
            'categories' => $categories,
            'posts' => $posts,
            'user' => $this->getUser(),
            'wordcount' => $wordcount,
        ];
    }
}
