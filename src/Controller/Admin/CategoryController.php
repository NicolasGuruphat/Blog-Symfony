<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index(Request $request)
    {

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $listCategory = $categoryRepository->findAll();
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setName($category->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('Admin/allCategory.admin.html.twig', [
            'listCategory' => $listCategory,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/category/show/{id}", name="admin_show_category")
     */
    public function show($id, Request $request)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException(
                "Pas de category trouvé avec l'id " . $id
            );
        }
        $newCategory = new Category();
        $form = $this->createFormBuilder($newCategory)
            ->add('name', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCategory = $form->getData();
            $category->setName($newCategory->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('Admin/category.admin.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
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
