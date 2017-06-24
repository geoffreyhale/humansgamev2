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
     * @Template("OneThirtyWordsBundle:Post:index.html.twig")
     */
    public function indexAction(Post $post)
    {
        if ($this->getUser() !== $post->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your post.");
        }

        return [
            'post' => $post,
        ];
    }

    /**
     * @Route("/posts", name="posts")
     * @Template("OneThirtyWordsBundle:Post:posts.html.twig")
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

        return [
            'posts' => array_reverse($posts),
        ];
    }

    /**
     * @Route("/post/{id}/edit", name="postEdit")
     * @Template("OneThirtyWordsBundle:Post:edit.html.twig")
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
            ->add('submit', SubmitType::class, array('label' => 'Submit Post'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
        }

        return [
            'form' => $form->createView(),
            'post' => $post,
        ];
    }

    /**
     * @Route("/new-post-by-category/{category_id}", name="newPostByCategory")
     * @Template("OneThirtyWordsBundle:Post:edit.html.twig")
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
