<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index()
    {

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $listCategory = $categoryRepository->findAll();

        return $this->render('Admin/category.admin.html.twig', [
            'listCategory' => $listCategory,

        ]);
    }
    /**
     * @Route("/admin/category/delete/{id}", name="admin_delete_category")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $entityManager->remove($categoryRepository->find($id));
        $entityManager->flush();
        return $this->redirectToRoute('admin_category');
    }
}
