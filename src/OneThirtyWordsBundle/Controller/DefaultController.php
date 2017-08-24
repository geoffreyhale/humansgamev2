<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use OneThirtyWordsBundle\Service\OneThirtyService;
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
            return $this->render('OneThirtyWordsBundle:Home:splash.html.twig');
        }

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('today'),
            'user' => $this->getUser(),
        ]);

        $categories = $em->getRepository(Category::class)->findBy([
            'user' => $this->getUser(),
            'hide' => false,
        ]);

        uasort($categories, function ($c1, $c2) {
            if ($c2->getName() == $c1->getName()) return 0;
            return $c2->getName() > $c1->getName() ? -1 : 1;
        });

        return $this->render('OneThirtyWordsBundle:Home:index.html.twig', array(
            'categories' => $categories,
            'posts' => $posts,
            'user' => $this->getUser(),
            'user130WordsCount' => $this->get('one_thirty_service')->getUser130WordsCount($this->getUser()),
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $this->get('one_thirty_service')->getUsersWith130WordsCounts($this->getUser());
        die;
    }
}
