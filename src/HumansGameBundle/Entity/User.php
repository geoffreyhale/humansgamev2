<?php
namespace HumansGameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Human[]
     *
     * @ORM\OneToMany(targetEntity="Human", mappedBy="user")
     */
    private $humans;

    /**
     * @var bool
     *
     * @ORM\Column(name="email_reminders", type="boolean", options={"default" : 0})
     */
    private $emailReminders = false;


    public function __construct()
    {
        parent::__construct();
        $this->categories = new ArrayCollection();
    }

    /**
     * Add human
     *
     * @param Human $human
     *
     * @return Human
     */
    public function addHuman(Human $human)
    {
        $this->humans[] = $human;

        return $this;
    }

    /**
     * Remove human
     *
     * @param Human $human
     */
    public function removeHuman(Human $human)
    {
        $this->humans->removeElement($human);
    }

    /**
     * Get humans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHumans()
    {
        return $this->humans;
    }

    /**
     * @return bool
     */
    public function isEmailReminders()
    {
        return $this->emailReminders;
    }

    /**
     * @param bool $emailReminders
     *
     * @return User
     */
    public function setEmailReminders($emailReminders)
    {
        $this->emailReminders = $emailReminders;

        return $this;
    }
}