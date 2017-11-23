<?php

namespace HumansGameBundle\Controller;

use HumansGameBundle\Entity\Human;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/human")
 */
class HumanController extends Controller
{
    /**
     * @Route("/create", name="human_create")
     */
    public function createHumanAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $human = new Human();

        $form = $this->createFormBuilder($human)
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $human = $form->getData();
            $human->setUser($this->getUser());
            $em->persist($human);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('HumansGameBundle:pages:human_create.html.twig', array(
            'human_create_form' => $form->createView(),
            'human' => $human,
        ));
    }
}
