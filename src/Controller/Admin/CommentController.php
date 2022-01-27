<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment")
     */
    public function index()
    {

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $listComment = $commentRepository->findAll();
        return $this->render('Admin/allComment.admin.html.twig', [
            'listComment' => $listComment,
        ]);
    }
    /**
     * @Route("/admin/comment/show/{id}", name="admin_show_comment")
     */
    public function show($id)
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);
        if (!$comment) {
            throw $this->createNotFoundException(
                "Pas de commentaire trouvé avec l'id " . $id
            );
        }

        return $this->render('Admin/comment.admin.html.twig', [
            'comment' => $comment,
        ]);
    }
    /**
     * @Route("/admin/comment/delete/{id}", name="admin_delete_comment")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $entityManager->remove($commentRepository->find($id));
        $entityManager->flush();
        return $this->redirectToRoute('admin_comment');
    }
    /**
     * @Route("/admin/comment/validate/{id}", name="admin_validate_comment")
     */
    public function validate($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $commentRepository->find($id);
        $comment->setValid(True);
        $entityManager->flush();
        return $this->redirectToRoute('admin_comment');
    }
}
