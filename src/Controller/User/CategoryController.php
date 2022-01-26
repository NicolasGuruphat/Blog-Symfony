<?php

namespace App\Controller\User;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $listCategory = $categoryRepository->findAll();
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $listPost = $postRepository->findAll();
        return $this->render('User/category.html.twig', [
            'listPost' => $listPost,
            'listCategory' => $listCategory,
        ]);
    }
    /**
     * @Route("/category/create", name="category.create")
     */
    public function create(): Response
    {
        // On crée un nouveau objet Post
        $category = new \App\Entity\Category();
        $category->setName("Category2");
        // On récupère le manager des entities
        $entityManager = $this->getDoctrine()->getManager();

        // On dit à Doctrine que l'on veut sauvegarder le Post
        // (Pas encore de requête faite en base)
        $entityManager->persist($category);

        // La/les requêtes sont exécutées (i.e. la requête INSERT) 
        $entityManager->flush();

        return $this->render('User/category.html.twig', [
            'category' => $category,
        ]);
    }
    /**
     * @Route("/category/{categoryId}", name="postByCategory");
     */
    public function listPostByCategory($categoryId)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($categoryId);
        $listPost  = $category->getPosts();

        if (!$listPost) {
            throw $this->createNotFoundException(
                "Pas de Post trouvé"
            );
        }
        return $this->render('User/postByCategory.html.twig', [

            'listPost' => $listPost,
        ]);
    }
}
