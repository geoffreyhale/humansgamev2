<?php

namespace OneThirtyWordsBundle\Controller;

use OneThirtyWordsBundle\Entity\Category;
use OneThirtyWordsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/{id}", name="category")
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
