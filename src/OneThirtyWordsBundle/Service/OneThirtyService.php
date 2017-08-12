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
        $daysWords = array();
        foreach ($posts as $post) {
            $postDateKey = $post->getDate()->format('Ymd');

            if (isset($daysWords[$postDateKey])) {
                $daysWords[$postDateKey]['wordCount'] += str_word_count($post->getBody());
            } else {
                $daysWords[$postDateKey] = array(
                    'DateTime' => $post->getDate(),
                    'wordCount' => str_word_count($post->getBody()),
                );
            }

            $daysWords[$postDateKey]['130'] = $daysWords[$postDateKey]['wordCount'] >= 130;
        }

        $user130WordsCount = count(array_filter($daysWords, function($dayWords){
            return $dayWords['130'];
        }));

        return $user130WordsCount;
    }
}