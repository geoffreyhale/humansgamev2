<?php

namespace HumansGameBundle\Controller;

use HumansGameBundle\Entity\Human;
use HumansGameBundle\Entity\HumanThing;
use HumansGameBundle\Entity\Thing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/farm")
 */
class FarmController extends Controller
{
    /**
     * @Route("/", name="farm")
     */
    public function farmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sessionHumanId = $this->get('session')->get('human')->id;
        $human = $em->getRepository(Human::class)->findOneBy(['id' => $sessionHumanId]);

        if ($this->getUser() !== $human->getUser()) {
            throw new \Exception("USER ACCESS DENIED: You cannot Be this Human. This is not your Human to Be. Unless you want to make them an offer ($$)?");
        }

        $seed = $em->getRepository(Thing::class)->findOneBy(['name' => 'seed']);
        $seeds = $em->getRepository(HumanThing::class)->findOneBy(['human' => $human, 'thing' => $seed]);

        return $this->render('HumansGameBundle:pages:farm.html.twig', array(
            'human' => $human,
            'seeds' => $seeds,
        ));
    }

    /**
     * @Route("/get-seeds", name="getSeeds")
     */
    public function getSeedsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sessionHumanId = $this->get('session')->get('human')->id;
        /** @var Human $human */
        $human = $em->getRepository(Human::class)->findOneBy(['id' => $sessionHumanId]);

        if ($this->getUser() !== $human->getUser()) {
            throw new \Exception("USER ACCESS DENIED: You cannot Be this Human. This is not your Human to Be. Unless you want to make them an offer ($$)?");
        }

        $seed = $em->getRepository(Thing::class)->findOneBy(['name' => 'seed']);

        if (!$seed) {
            $seed = (new Thing())->setName('seed');
        }

        $humanThing = $em->getRepository(HumanThing::class)->findOneBy(['human' => $human->getId(), 'thing' => $seed->getId()]);

        if (!$humanThing) {
            $humanThing = (new HumanThing())->setHuman($human)->setThing($seed)->setQuantity(1);
        } else {
            $humanThing->addQuantity(1);
        }

        $human->addThing($humanThing);
        $em->persist($seed);
        $em->persist($humanThing);
        $em->persist($human);
        $em->flush();

        return $this->redirectToRoute('farm');
    }
}
