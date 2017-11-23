<?php

namespace HumansGameBundle\Controller;

use HumansGameBundle\Entity\Human;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/human")
 */
class HumanController extends Controller
{
    /**
     * @Route("/{id}/being", requirements={"id" = "\d+"}, name="human_being")
     * @Method({"GET"})
     */
    public function humanBeingAction(Request $request, Human $human)
    {
        if ($this->getUser() !== $human->getUser()) {
            throw new \Exception("USER ACCESS DENIED: You cannot Be this Human. This is not your Human to Be. Unless you want to make them an offer ($$)?");
        }

        $humanBeing = new \StdClass();
        $humanBeing->id = $human->getId();
        $humanBeing->name = $human->getName();

        $this->get('session')->set('human', $humanBeing);

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, name="human")
     * @Method({"GET"})
     */
    public function humanAction(Request $request, Human $human)
    {
        $response = new \StdClass();
        $response->human = new \StdClass();
        $response->human->id = $human->getId();
        $response->human->name = $human->getName();

        return new JsonResponse($response);
    }

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

            return $this->redirectToRoute('human_being', array('id' => $human->getId()));
        }

        return $this->render('HumansGameBundle:pages:human_create.html.twig', array(
            'human_create_form' => $form->createView(),
            'human' => $human,
        ));
    }
}
