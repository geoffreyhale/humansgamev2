<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{id}", requirements={"id" = "\d+"}, name="category")
     * @Template("OneThirtyWordsBundle:Category:category.html.twig")
     */
    public function indexAction(Category $category)
    {
        if ($this->getUser() !== $category->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your category.");
        }

        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)
            ->findBy(
                array(
                    'category' => $category,
                ),
                array(
                    'date' => 'DESC',
                )
            );

        return [
            'category' => $category,
            'posts' => $posts,
        ];
    }

    /**
     * @Route("/category/new", name="newCategory")
     * @Template("OneThirtyWordsBundle:Category:new.html.twig")
     */
    public function newCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setUser($this->getUser());
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category', array(
                'id'  => $category->getId(),
            ));
        }

        return [
            'form' => $form->createView(),
            'category' => $category,
        ];
    }

    /**
     * @Route("/category/{id}/edit", name="categoryEdit")
     * @Template("OneThirtyWordsBundle:Category:edit.html.twig")
     */
    public function categoryEditAction(Request $request, Category $category)
    {
        if ($this->getUser() !== $category->getUser()) {
            throw new \Exception("ACCESS DENIED: This is not your category.");
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Update'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
        }

        return [
            'form' => $form->createView(),
            'category' => $category,
        ];
    }

    /**
     * @Route("/categories", name="categories")
     * @Template("OneThirtyWordsBundle:Category:categories.html.twig")
     */
    public function categoriesAction()
    {
        $categories = $this->getUser()->getCategories()->getValues();

        uasort($categories, function ($c1, $c2) {
            if ($c2->getName() == $c1->getName()) return 0;
            return $c2->getName() > $c1->getName() ? -1 : 1;
        });

        $categoriesViewArray = [];

        foreach($categories as $category) {
            $categoriesViewArray[$category->getId()] = array(
                'id' => $category->getId(),
                'name' => $category->getName(),
                'post_count' => $category->getPosts()->count(),
            );
        }

        return [
            'categories' => $categoriesViewArray,
        ];
    }
}
