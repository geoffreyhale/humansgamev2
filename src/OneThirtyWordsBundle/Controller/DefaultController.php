<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use OneThirtyWordsBundle\Entity\User;
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
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            if ($user->getCategories()->isEmpty()) {
                $category = (new Category())
                    ->setName('Default')
                    ->setUser($user);
                $em->persist($category);
                $em->flush();
            }

            /** @var Post $post */
            $post = $em->getRepository(Post::class)->findOneBy([
                'date' => new \DateTime('today'),
                'user' => $user,
            ]);

            if (!$post) {
                $post = new Post();
                $post->setDate(new \DateTime);
            }

            if (!$post->getCategory()) {
                $post->setCategory($user->getCategories()->first());
            }

            $form = $this->createFormBuilder($post)
                ->add('body', TextareaType::class)
                ->add('submit', SubmitType::class, array('label' => 'Submit Post'))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Post $data */
                $data = $form->getData()
                    ->setCategory($user->getCategories()->first())
                    ->setUser($user);

                $em->persist($data);
                $em->flush();
            }
        }

        return [
            'form' => $form->createView(),
            'post' => $post,
        ];
    }
}
