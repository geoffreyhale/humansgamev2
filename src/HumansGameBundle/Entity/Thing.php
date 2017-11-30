<?php

namespace HumansGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(name="thing")
 * @ORM\Entity
 */
class Thing
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
     * @var HumanThing
     *
     * @ORM\OneToMany(targetEntity="HumanThing", mappedBy="thing")
     */
    private $human;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=191, nullable=false, unique=true)
     */
    private $name;

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
     * Set description
     *
     * @param string $description
     *
     * @return Thing
     */
    public function setBody($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set human
     *
     * @param Human $human
     *
     * @return Thing
     */
    public function setHuman(Human $human)
    {
        $this->human = $human;

        return $this;
    }

    /**
     * Get human
     *
     * @return Human
     */
    public function getHuman()
    {
        return $this->human;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Thing
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}

