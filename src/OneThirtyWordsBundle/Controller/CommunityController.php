<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use OneThirtyWordsBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/community")
 */
class CommunityController extends Controller
{
    /**
     * @Route("/", name="community")
     */
    public function communityAction()
    {
        return $this->render('OneThirtyWordsBundle:Community:index.html.twig');
    }

    /**
     * @Route("/recent-users-by-post-date", name="getRecentUsersByPostDate")
     */
    public function getRecentUsersByPostDateAction($limit = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->getRecent();

        $postsBatchedByDate = [];

        /** @var Post $post */
        foreach ($posts as $post) {
            $postsBatchedByDate[$post->getDate()->format('Y-m-d')][] = $post;
        }

        if ($limit > 0) {
            $postsBatchedByDate = array_slice($postsBatchedByDate, 0, $limit);
        }

        return $this->render('OneThirtyWordsBundle:Community:partials/recent_users_by_post_date.html.twig', array(
            'postsBatchedByDate' => $postsBatchedByDate
        ));
    }
}