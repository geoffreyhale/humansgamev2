<?php

namespace HumansGameBundle\Controller;

use HumansGameBundle\Entity\Human;
use HumansGameBundle\Entity\HumanThing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user) {
            //@todo (hydration) there must be a better way
            foreach ($user->getHumans() as $human) { }
        }

        $em = $this->getDoctrine()->getManager();
        $humans = $em->getRepository(Human::class)->findAll();
        $humansModels = [];
        /** @var Human $human */
        foreach ($humans as $human) {
            $humanModel = (new \StdClass());
            $humanModel->name = $human->getName();
            $humanModel->seeds = $em->getRepository(HumanThing::class)->getHumanThingQuantity($human, 'seed');
            $humansModels[$human->getId()] = $humanModel;
        }
        usort($humansModels, function($a, $b) {
            if ($a->seeds == $b->seeds) {
                return (strcasecmp($a->name, $b->name));
            }
            return ($a->seeds > $b->seeds) ? -1 : 1;
        });

        return $this->render('HumansGameBundle::index.html.twig', array(
            'user' => $this->getUser(),
            'humans' => $humansModels,
        ));
    }
}
