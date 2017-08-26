<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/{user}", name="getUser", requirements={"user": "\d+"})
     */
    public function userAction(User $user)
    {
        if ($this->getUser() !== $user) {
            throw new \Exception("ACCESS DENIED: Access to user profiles is currently limited to your own.");
        }

        return $this->render('OneThirtyWordsBundle:User:user.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @Route("/user/{user}/edit-display-name", name="editUserDisplayName")
     */
    public function editUserDisplayNameAction(Request $request, User $user)
    {
        if ($this->getUser() !== $user) {
            throw new \Exception("ACCESS DENIED: This is not your user.");
        }

        if ($user->getDisplayName()) {
            throw new \Exception("ACCESS DENIED: You have already chosen a display name. Multiple display name changes are for paid members only.");
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($user)
            ->add('displayName', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $displayName = $form->getData()->getDisplayName();

            $usersWithDisplayNameAsDisplayName = $em->getRepository(User::class)->findBy([
                'displayName' => $displayName,
            ]);

            $usersWithDisplayNameAsUsername = $em->getRepository(User::class)->findBy([
                'username' => $displayName,
            ]);

            if ($usersWithDisplayNameAsDisplayName || $usersWithDisplayNameAsUsername) {
                return $this->render('OneThirtyWordsBundle:User:editDisplayName.html.twig', array(
                    'form' => $form->createView(),
                    'message' => array('warning', sprintf('Display name %s is not available. Please try a different display name.', $displayName)),
                    'user' => $user,
                ));
            }

            $user->setDisplayName($displayName);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('getUser', array(
                'user' => $user->getId()
            ));
        }

        return $this->render('OneThirtyWordsBundle:User:editDisplayName.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }
}