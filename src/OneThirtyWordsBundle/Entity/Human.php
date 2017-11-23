<?php

namespace OneThirtyWordsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="human")
 * @ORM\Entity(repositoryClass="OneThirtyWordsBundle\Repository\CategoryRepository")
 */
class Human
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Thing[]
     *
     * @ORM\OneToMany(targetEntity="Thing", mappedBy="human")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $things;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="humans")
     */
    private $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add thing
     *
     * @param Thing $thing
     *
     * @return self
     */
    public function addPost(Thing $thing)
    {
        $this->things[] = $thing;

        return $this;
    }

    /**
     * Remove thing
     *
     * @param Thing $thing
     */
    public function removePost(Thing $thing)
    {
        $this->things->removeElement($thing);
    }

    /**
     * Get things
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThings()
    {
        return $this->things;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
