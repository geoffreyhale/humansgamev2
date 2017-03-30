<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("OneThirtyWordsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        if ($user) {
            $em = $this->getDoctrine()->getManager();

            $post = $em->getRepository(Post::class)->findOneBy(['date' => new \DateTime('today')]);

            if (!$post) {
                $post = new Post();
                $post->setDate(new \DateTime);
            }

            $form = $this->createFormBuilder($post)
                ->add('body', TextareaType::class)
                ->add('submit', SubmitType::class, array('label' => 'Submit Post'))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $em->persist($data);
                $em->flush();
            }
        }

        return [
            'form' => $form->createView(),
            'date' => $post->getDate()->format('l, F jS'),
            'user' => $user,
        ];
    }
}
