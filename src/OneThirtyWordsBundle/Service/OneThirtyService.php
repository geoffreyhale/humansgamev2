<?php

namespace OneThirtyWordsBundle\Service;

use Doctrine\ORM\EntityManager;
use OneThirtyWordsBundle\Entity\Post;
use OneThirtyWordsBundle\Entity\User;

class OneThirtyService
{
    /** @var  EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get User 130 Words Count
     *
     * Get the number of days the user wrote at least 130 words
     *
     * @param User $user
     * @return int
     */
    public function getUser130WordsCount(User $user)
    {
        $posts = $this->em->getRepository(Post::class)->getPostsByUser($user);

        /**
         * Days Words
         *
         * Keyed by yyyymmdd from post(s) DateTime
         * 'DateTime'  => DateTime from post(s)
         * 'wordCount' => string accumulates count of words from all posts per date
         * '130'       => bool if at least 130 words that day
         */
        $userDaysWords = array();
        foreach ($posts as $post) {
            $postDateKey = $post->getDate()->format('Ymd');

            if (isset($userDaysWords[$postDateKey])) {
                $userDaysWords[$postDateKey]['wordCount'] += str_word_count($post->getBody());
            } else {
                $userDaysWords[$postDateKey] = array(
                    'DateTime' => $post->getDate(),
                    'wordCount' => str_word_count($post->getBody()),
                );
            }

            $userDaysWords[$postDateKey]['130'] = $userDaysWords[$postDateKey]['wordCount'] >= 130;
        }

        $user130WordsCount = count(array_filter($userDaysWords, function($dayWords){
            return $dayWords['130'];
        }));

        return $user130WordsCount;
    }
    
    public function getUsersWith130WordsCounts()
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $usersData = array();

        foreach ($users as $user) {
            $usersData[$user->getId()] = array(
                'username' => $user->getUsername(),
                '130' => $this->getUser130WordsCount($user),
            );
        }

        uasort($usersData, function ($u1, $u2) {
            if ($u2['130'] == $u1['130']) return 0;
            return $u2['130'] < $u1['130'] ? -1 : 1;
        });

        return $usersData;
    }
}