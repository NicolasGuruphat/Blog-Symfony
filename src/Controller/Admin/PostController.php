<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {

        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $listPost = $postRepository->findAll();
        return $this->render('Admin/allPost.admin.html.twig', [
            'listPost' => $listPost,
        ]);
    }
    /**
     * @Route("/admin/post/show/{id}", name="admin_show_post")
     */
    public function show($id)
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                "Pas de post trouvé avec l'id " . $id
            );
        }

        return $this->render('Admin/post.admin.html.twig', [
            'post' => $post,
        ]);
    }
    /**
     * @Route("/admin/post/delete/{id}", name="admin_delete_post")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $entityManager->remove($postRepository->find($id));
        $entityManager->flush();
        return $this->redirectToRoute('admin_post');
    }
}
