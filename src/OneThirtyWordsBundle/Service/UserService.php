<?php

namespace OneThirtyWordsBundle\Service;

use Doctrine\ORM\EntityManager;
use OneThirtyWordsBundle\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserService
{
    /** @var  EntityManager */
    private $em;

    /** @var  TokenStorage */
    private $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function getUserWordCountToday()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user) {
            return null;
        }

        $posts = $this->em->getRepository(Post::class)->findBy([
            'date' => new \DateTime('today'),
            'user' => $user,
        ]);

        $wordcount = 0;
        foreach ($posts as $post) {
            $wordcount += str_word_count($post->getBody());
        }

        return $wordcount;
    }
}