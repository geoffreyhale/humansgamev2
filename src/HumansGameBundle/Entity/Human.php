<?php

namespace HumansGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="human")
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var HumanThing[]
     *
     * @ORM\OneToMany(targetEntity="HumanThing", mappedBy="human", cascade={"persist"})
     */
    private $thing;

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
     * @param HumanThing $thing
     *
     * @return self
     */
    public function addThing(HumanThing $thing)
    {
        $this->things[] = $thing;

        return $this;
    }

    /**
     * Remove thing
     *
     * @param HumanThing $thing
     */
    public function removeThing(HumanThing $thing)
    {
        $this->thing->removeElement($thing);
    }

    /**
     * Get things
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getThings()
    {
        return $this->thing;
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

