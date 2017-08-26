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
     * Calculate Posts Data By Date
     *
     * Keyed by format('Ymd') (yyyymmdd) from post(s) DateTime
     * 'DateTime'  => DateTime from post(s)
     * 'wordCount' => string accumulates count of words from all posts per date
     * '130'
     *
     * @param array $posts
     * @return array $postsDataByDate
     */
    private function calculatePostsDataByDate($posts)
    {
        $postsDataByDate = array();

        foreach ($posts as $post) {
            $date = $post->getDate()->format('Ymd');

            if (isset($postsDataByDate[$date])) {
                $postsDataByDate[$date]['wordCount'] += str_word_count($post->getBody());
            } else {
                $postsDataByDate[$date] = array(
                    'DateTime' => $post->getDate(),
                    'wordCount' => str_word_count($post->getBody()),
                );
            }

            $postsDataByDate[$date]['130'] = $postsDataByDate[$date]['wordCount'] >= 130;
        }

        return $postsDataByDate;
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

        $postsDataByDate = $this->calculatePostsDataByDate($posts);

        $user130WordsCount = count(array_filter($postsDataByDate, function($dateData){
            return $dateData['130'];
        }));

        return $user130WordsCount;
    }

    public function getUser130WordsStreak(User $user)
    {
        $posts = $this->em->getRepository(Post::class)->getPostsByUser($user);

        $postsDataByDate = $this->calculatePostsDataByDate($posts);

        $streak = 0;
        $dateTimeIterator = new \DateTime('now');

        if (
            array_key_exists($dateTimeIterator->format('Ymd'), $postsDataByDate)
            && $postsDataByDate[$dateTimeIterator->format('Ymd')]['130']
        ) {
            $streak++;
        }

        $streakContinues = true;
        while ($streakContinues) {
            $dateTimeIterator->sub(new \DateInterval('P1D'));

            if (
                array_key_exists($dateTimeIterator->format('Ymd'), $postsDataByDate)
                && $postsDataByDate[$dateTimeIterator->format('Ymd')]['130']
            ) {
                $streak++;
            } else {
                $streakContinues = false;
            }
        }

        return $streak;
    }

    public function getUsersByStreak()
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $usersData = array();

        foreach ($users as $user) {
            $userCurrentStreak = $this->getUser130WordsStreak($user);

            if ($userCurrentStreak > 0) {
                $usersData[$user->getId()] = array(
                    'streak' => $userCurrentStreak,
                    'displayName' => $user->getDisplayName() ? $user->getDisplayName() : $user->getUsername(),
                );
            }
        }

        uasort($usersData, function ($u1, $u2) {
            if ($u2['streak'] == $u1['streak']) return 0;
            return $u2['streak'] < $u1['streak'] ? -1 : 1;
        });

        return $usersData;
    }
    
    public function getUsersWith130WordsCounts($min = 0, $limit = 0)
    {
        $users = $this->em->getRepository(User::class)->findAll();

        $usersData = array();

        foreach ($users as $user) {
            $user130WordsCount = $this->getUser130WordsCount($user);

            if ($user130WordsCount >= $min) {
                $usersData[$user->getId()] = array(
                    '130' => $user130WordsCount,
                    'displayName' => $user->getDisplayName() ? $user->getDisplayName() : $user->getUsername(),
                );
            }
        }

        uasort($usersData, function ($u1, $u2) {
            if ($u2['130'] == $u1['130']) return 0;
            return $u2['130'] < $u1['130'] ? -1 : 1;
        });

        if ($limit > 0) {
            return array_slice($usersData, 0, $limit);
        }

        return $usersData;
    }
}