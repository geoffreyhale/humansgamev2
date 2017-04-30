<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use OneThirtyWordsBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/stats")
 */
class StatsController extends Controller
{
    /**
     * @Route("/", name="stats")
     * @Template("OneThirtyWordsBundle:Stats:index.html.twig")
     */
    public function statsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findAll();

        $usersData = [];

        /** @var User $user */
        foreach ($users as $user) {
            $usersData[$user->getId()] = ['wordcount' => 0];
            $categories = $user->getCategories();

            /** @var Category $category */
            foreach ($categories as $category) {
                $posts = $category->getPosts();

                /** @var Post $post */
                foreach ($posts as $post) {
                    $usersData[$user->getId()]['wordcount'] += str_word_count($post->getBody());
                }
            }
        }

        uasort($usersData, function ($u1, $u2) {
            if ($u2['wordcount'] == $u1['wordcount']) return 0;
            return $u2['wordcount'] < $u1['wordcount'] ? -1 : 1;
        });

        return [
            'users' => $usersData
        ];
    }
}