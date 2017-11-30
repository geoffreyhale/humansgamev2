<?php

namespace HumansGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * HumanThing
 *
 * @ORM\Table(name="human_thing")
 * @ORM\Entity
 */
class HumanThing
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="bigint")
     */
    private $quantity;

    /**
     * @var Human
     *
     * @ORM\ManyToOne(targetEntity="Human", inversedBy="thing")
     */
    private $human;

    /**
     * @var Thing
     *
     * @ORM\ManyToOne(targetEntity="Thing", inversedBy="human", fetch="EAGER")
     */
    private $thing;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return HumanThing
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param $quantity
     * @return HumanThing
     */
    public function addQuantity($quantity)
    {
        $this->setQuantity($this->getQuantity() + $quantity);

        return $this;
    }

    /**
     * @param $quantity
     * @return HumanThing
     */
    public function removeQuantity($quantity)
    {
        $this->setQuantity($this->getQuantity() - $quantity);

        return $this;
    }

    /**
     * Set human
     *
     * @param Human $human
     *
     * @return HumanThing
     */
    public function setHuman(Human $human = null)
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
     * Set thing
     *
     * @param Thing $thing
     *
     * @return HumanThing
     */
    public function setThing(Thing $thing = null)
    {
        $this->thing = $thing;

        return $this;
    }

    /**
     * Get thing
     *
     * @return Thing
     */
    public function getThing()
    {
        return $this->thing;
    }
}
