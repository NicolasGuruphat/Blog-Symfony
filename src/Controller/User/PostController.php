<?php
// src/Controller/DefaultController.php
namespace App\Controller\User;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{


  /**
   * @Route("/post/{id}",name="show");
   */

  public function show($id)
  {
    // On récupère le `repository` en rapport avec l'entity `Post` 
    $postRepository = $this->getDoctrine()->getRepository(Post::class);
    // On fait appel à la méthode générique `find` qui permet de SELECT en fonction d'un Id
    $post = $postRepository->find($id);

    if (!$post) {
      throw $this->createNotFoundException(
        "Pas de Post trouvé avec l'id " . $id
      );
    }

    return $this->render('User/post.html.twig', [
      'post' => $post,
    ]);

    /*return $this->render('User/post.html.twig', [
      'id' => $id,
    ]);*/
  }

  /**
   * @Route("/posts", name="listPost");
   */
  public function listPost()
  {
    return $this->render('User/listPost.html.twig', []);
  }
}
