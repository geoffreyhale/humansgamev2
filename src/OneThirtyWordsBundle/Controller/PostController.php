<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/post/{post}", name="post")
     */
    public function indexAction(Post $post)
    {
        if ($this->getUser() !== $post->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your post.");
        }

        return $this->render('OneThirtyWordsBundle:Post:index.html.twig', array(
            'post' => $post,
        ));
    }

    /**
     * @Route("/posts", name="posts")
     */
    public function postsAction()
    {
        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)
            ->findBy(
                array(
                    'user' => $this->getUser(),
                ),
                array(
                    'date' => 'ASC',
                )
            );

        return $this->render('OneThirtyWordsBundle:Post:posts.html.twig', array(
            'posts' => array_reverse($posts),
        ));
    }

    /**
     * @Route("/post/{id}/edit", name="postEdit")
     */
    public function postEditAction(Request $request, Post $post)
    {
        if ($post->getUser() !== $this->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your post.");
        }

        if ($post->getDate()->format('Ymd') != (new \DateTime)->format('Ymd')) {
            throw new \Exception("CANNOT EDIT: You can only edit posts written today.");
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($post)
            ->add('body', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
        }

        return $this->render('OneThirtyWordsBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
        ));
    }

    /**
     * @Route("/new-post-by-category/{category_id}", name="newPostByCategory")
     */
    public function newPostByCategoryAction($category_id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository(Category::class)->find($category_id);

        if ($category->getUser() !== $this->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your category.");
        }

        $post = $em->getRepository(Post::class)->findOneBy([
            'category' => $category,
            'date' => new \DateTime('today'),
        ]);

        if (!$post) {
            $post = (new Post())
                ->setCategory($category)
                ->setDate(new \DateTime)
                ->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
        }

        return $this->forward('OneThirtyWordsBundle:Post:postEdit', array(
            'id'  => $post->getId(),
        ));
    }
}
