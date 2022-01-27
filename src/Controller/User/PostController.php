<?php
// src/Controller/DefaultController.php
namespace App\Controller\User;

use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostController extends AbstractController
{

  /**
   * @Route("/post/create", name="post.create")
   */
  public function create(): Response
  {
    // On crée un nouveau objet Post
    $post = new \App\Entity\Post();
    $post->setTitle('Premier article');
    $post->setContent('Mon contenu');
    $post->setDescription("Ma description");
    $post->setSlug('premier-article');
    $post->setCreatedAt(new \DateTimeImmutable('@' . strtotime('now')));
    $post->setUpdatedAt(new \DateTimeImmutable('@' . strtotime('now')));
    $post->setPublishedAt(new \DateTimeImmutable('@' . strtotime('now')));
    // On récupère le manager des entities
    $entityManager = $this->getDoctrine()->getManager();

    // On dit à Doctrine que l'on veut sauvegarder le Post
    // (Pas encore de requête faite en base)
    $entityManager->persist($post);

    // La/les requêtes sont exécutées (i.e. la requête INSERT) 
    $entityManager->flush();

    return $this->render('User/post.html.twig', [
      'post' => $post,
    ]);
  }

  /**
   * @Route("/show/{id}",name="show");
   */

  public function show($id, Request $request)
  {
    // On récupère le `repository` en rapport avec l'entity `Post` 
    $postRepository = $this->getDoctrine()->getRepository(Post::class);
    // On fait appel à la méthode générique `find` qui permet de SELECT en fonction d'un Id
    $post = $postRepository->find($id);
    $listPost = $postRepository->findAll();
    if (!$post) {
      throw $this->createNotFoundException(
        "Pas de Post trouvé avec l'id " . $id
      );
    }
    $comment = new Comment();

    $form = $this->createFormBuilder($comment)
      ->add('username', TextType::class)
      ->add('content', TextareaType::class)
      ->add('save', SubmitType::class)
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $comment = $form->getData();
      $comment->setCreatedAt(new \DateTimeImmutable('@' . strtotime('now')));
      $comment->setPosts($post);
      $comment->setValid(True);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($comment);
      $entityManager->flush();
      $formResponse = "Commentaire ajouté";
    } else {
      $formResponse = "";
    }
    $listComment = $post->getComments($post);
    return $this->render('User/post.html.twig', [
      'post' => $post,
      'listPost' => $listPost,
      'form' => $form->createView(),
      'formResponse' => $formResponse,
      'listComment' => $listComment //->toArray(),
    ]);
  }


  /**
   * @Route("/posts", name="listPost");
   */
  public function listPost()
  {
    $postRepository = $this->getDoctrine()->getRepository(Post::class);
    // On fait appel à la méthode générique `find` qui permet de SELECT en fonction d'un Id
    $listPost = $postRepository->findAll();
    if (!$listPost) {
      throw $this->createNotFoundException(
        "Pas de Post trouvé"
      );
    }

    return $this->render('User/listPost.html.twig', [

      'listPost' => $listPost,
    ]);
  }
}
