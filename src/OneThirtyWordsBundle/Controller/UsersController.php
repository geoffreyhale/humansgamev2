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

    /**
     * @Route("/total-word-count", name="getUsersByTotalWordCount")
     */
    public function getUsersByTotalWordCountAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findAll();

        $usersData = [];

        /** @var User $user */
        foreach ($users as $user) {
            $usersData[$user->getId()] = ['wordcount' => 0, 'username' => $user->getUsername()];
            $posts = $em->getRepository(Post::class)->getPostsByUser($user);

            /** @var Post $post */
            foreach ($posts as $post) {
                $usersData[$user->getId()]['wordcount'] += str_word_count($post->getBody());
            }
        }

        uasort($usersData, function ($u1, $u2) {
            if ($u2['wordcount'] == $u1['wordcount']) return 0;
            return $u2['wordcount'] < $u1['wordcount'] ? -1 : 1;
        });

        return $this->render('OneThirtyWordsBundle:Users:partials/users_by_total_word_count.html.twig', array(
            'users' => $usersData
        ));
    }

    /**
     * @Route("/today", name="getUsersToday")
     */
    public function getUsersTodayAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('today'),
        ]);

        $usersData = [];
        foreach ($posts as $post) {
            if (!array_key_exists($post->getUser()->getUsername(), $usersData)) {
                $usersData[$post->getUser()->getUsername()]['wordcount'] = 0;
            }

            $usersData[$post->getUser()->getUsername()]['wordcount'] += str_word_count($post->getBody());
        }

        // sort by word count
        uasort($usersData, function ($u1, $u2) {
            if ($u2['wordcount'] == $u1['wordcount']) return 0;
            return $u2['wordcount'] > $u1['wordcount'] ? 1 : -1;
        });

        return $this->render('OneThirtyWordsBundle:Users:partials/community_today.html.twig', array(
            'users' => $usersData
        ));
    }
}