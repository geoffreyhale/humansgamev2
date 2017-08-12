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

        $categories = $em->getRepository(Category::class)->findBy([
            'user' => $this->getUser(),
            'hide' => false,
        ]);

        uasort($categories, function ($c1, $c2) {
            if ($c2->getName() == $c1->getName()) return 0;
            return $c2->getName() > $c1->getName() ? -1 : 1;
        });

        /**
         * Today Users
         */
        $todayUsers = [];

        $postsToday = $em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('today'),
        ]);

        foreach ($postsToday as $post) {
            if (!array_key_exists($post->getUser()->getUsername(), $todayUsers)) {
                $todayUsers[$post->getUser()->getUsername()]['wordcount'] = 0;
            }

            $todayUsers[$post->getUser()->getUsername()]['wordcount'] += str_word_count($post->getBody());
        }

        // sort by wordcount
        uasort($todayUsers, function ($u1, $u2) {
            if ($u2['wordcount'] == $u1['wordcount']) return 0;
            return $u2['wordcount'] > $u1['wordcount'] ? 1 : -1;
        });

        /**
         * Yesterday Users
         */
        $yesterdayUsers = [];

        $postsYesterday = $em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('yesterday'),
        ]);

        foreach ($postsYesterday as $post) {
            if (!array_key_exists($post->getUser()->getUsername(), $yesterdayUsers)) {
                $yesterdayUsers[$post->getUser()->getUsername()]['wordcount'] = 0;
            }

            $yesterdayUsers[$post->getUser()->getUsername()]['wordcount'] += str_word_count($post->getBody());
        }

        // sort by wordcount
        uasort($yesterdayUsers, function ($u1, $u2) {
            if ($u2['wordcount'] == $u1['wordcount']) return 0;
            return $u2['wordcount'] > $u1['wordcount'] ? 1 : -1;
        });

        return [
            'categories' => $categories,
            'posts' => $posts,
            'todayUsers' => $todayUsers,
            'user' => $this->getUser(),
            'user130WordsCount' => $this->get('one_thirty_service')->getUser130WordsCount($this->getUser()),
            'wordcount' => $wordcount,
            'yesterdayUsers' => $yesterdayUsers,
        ];
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $oneThirtyService = $this->get('one_thirty_service');
        $oneThirtyService->getUser130WordsCount($this->getUser());
        die;
    }
}
