<?php

namespace App\Controller\User;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $listPost = $postRepository->findAll();
        return $this->render('User/category.html.twig', [
            'listPost' => $listPost,
        ]);
    }
}
