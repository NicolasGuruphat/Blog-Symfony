<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index(Request $request)
    {

        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $listPost = $postRepository->findAll();
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('content', TextareaType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setTitle($post->getTitle());
            $post->setDescription($post->getDescription());
            $post->setContent($post->getContent());
            $slugger = new AsciiSlugger();
            $post->setSlug($slugger->slug($post->getTitle()));
            $post->setCreatedAt(new \DateTimeImmutable('@' . strtotime('now')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->render('Admin/Post/allPost.admin.html.twig', [
            'listPost' => $listPost,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/post/show/{id}", name="admin_show_post", methods={"GET", "POST"})
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

        return $this->render('Admin/Post/post.admin.html.twig', [
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
