<?php

namespace HumansGameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use HumansGameBundle\Entity\Human;

class HumanThingRepository extends EntityRepository
{
    /**
     * @param Human $human
     * @param array $thingNames
     * @return array
     */
    public function getHumanThingQuantity(Human $human, $thingName)
    {
        $qb = $this->createQueryBuilder('ht');
        $qb->join('ht.thing', 't');
        $qb->where('t.name = :thingName');
        $qb->setParameter('thingName', $thingName);
        $qb->andWhere('ht.human = :human');
        $qb->setParameter('human', $human);
        $result = $qb->getQuery()->getResult();

        if (!$result) {
            return 0;
        } else {
            return $result[0]->getQuantity();
        }
    }
}
