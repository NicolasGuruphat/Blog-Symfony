<?php

namespace App\Controller\User;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\String\Slugger\AsciiSlugger;


class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category")
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
        $category->setName("Category1");
        // On récupère le manager des entities
        $entityManager = $this->getDoctrine()->getManager();

        // On dit à Doctrine que l'on veut sauvegarder le Post
        // (Pas encore de requête faite en base)
        $entityManager->persist($category);
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        // La/les requêtes sont exécutées (i.e. la requête INSERT) 
        $entityManager->flush();
        return $this->render('User/createCategory.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/category/{categoryId}", name="postByCategory");
     */
    public function listPostByCategory($categoryId, Request $request)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($categoryId);
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('description', TextareaType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTimeImmutable('@' . strtotime('now')));
            $post->addCategory($category);
            $slugger = new AsciiSlugger();
            $post->setSlug($slugger->slug($post->getTitle()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('user_post_show', ['id' => $post->getId()]);
        }
        $listPost  = $category->getPosts();
        if (!$listPost) {
            throw $this->createNotFoundException(
                "Pas de Post trouvé"
            );
        }
        return $this->render('User/postByCategory.html.twig', [

            'listPost' => $listPost,
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    public function showCategories(): Response
    {
        /* Get all categories with valid posts attached and show them like :
        Category_name (number of valid posts)
        */
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAllPostsNotNull();

        return $this->render('User/base.user.html.twig', [
            'categories' => $categories,
        ]);
    }
}
