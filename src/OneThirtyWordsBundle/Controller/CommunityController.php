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
 * @Route("/community")
 */
class CommunityController extends Controller
{
    /**
     * @Route("/total-word-count", name="communityTotalWordCount")
     * @Template("OneThirtyWordsBundle:Community:total_word_count.html.twig")
     */
    public function communityTotalWordCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findBy(['enabled' => true]);

        $usersData = [];

        /** @var User $user */
        foreach ($users as $user) {
            $usersData[$user->getId()] = ['wordcount' => 0, 'username' => $user->getUsername()];
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